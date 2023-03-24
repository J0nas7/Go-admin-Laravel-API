<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    /* The table associated with the model.
     * @var string */
    protected $table = 'wc_order_stats';

    /* The primary key associated with the table.
     * @var string */
    protected $primaryKey = 'order_id';

    /* The model's default values for attributes.
     * @var array */
    protected $attributes = [
        'order_id' => 0,
        'date_created' => 0,
        'num_items_sold' => 0,
        'total_sales' => 0,
        'status' => 0,
        'customer_id' => 0,
        'date_paid' => 0,
        'date_completed' => 0
    ];

    protected $appends = [
        'adr' => '',
        'street_nr' => '',
        'postcode' => '',
        'city' => '',
        'delivery_data' => '',
        'delivery_range' => '',
        'delivery_deadline' => 'N/A',
        'theHelperIs' => 'N/A'
    ];

    // Append theHelperIs
    public function setTheHelperIsAttribute(string $theHelperIs) { $this->appends['theHelperIs'] = $theHelperIs; }
    public function getTheHelperIsAttribute() { return $this->appends['theHelperIs']; }
    
    // Append adr
    public function setAdrAttribute(string $adr) { $this->appends['adr'] = $adr; }
    public function getAdrAttribute() { return $this->appends['adr']; }
    
    // Append street_nr
    public function setStreetNrAttribute(string $street_nr) { $this->appends['street_nr'] = $street_nr; }
    public function getStreetNrAttribute() { return $this->appends['street_nr']; }
    
    // Append postcode
    public function setPostcodeAttribute(string $postcode) { $this->appends['postcode'] = $postcode; }
    public function getPostcodeAttribute() { return $this->appends['postcode']; }
        
    // Append city
    public function setCityAttribute(string $city) { $this->appends['city'] = $city; }
    public function getCityAttribute() { return $this->appends['city']; }

    // Append delivery_data
    public function setDeliveryDataAttribute(string $delivery_data) {
        $this->appends['delivery_data'] = $delivery_data;
        $this->setDeliveryDeadlineAttribute();
    }
    public function getDeliveryDataAttribute() { return $this->appends['delivery_data']; }
    
    // Append delivery_range
    public function setDeliveryRangeAttribute(string $delivery_range) {
        $delivery_range = stripslashes($delivery_range);
        $delivery_range = json_decode($delivery_range, true);
        $this->appends['delivery_range'] = $delivery_range;
        $this->setDeliveryDeadlineAttribute();
    }
    public function getDeliveryRangeAttribute() { return $this->appends['delivery_range']; }

    // Append delivery_deadline
    public function setDeliveryDeadlineAttribute() {
        $newDeadline = "";
        $theData = $this->appends['delivery_data'];
        $theRange = $this->appends['delivery_range'];
        if (strpos(" ".$theData, "timeframe")) {
            $delivery_range_1 = $theRange ? $theRange['timeframe-1'] : 'xx';
            $delivery_range_2 = $theRange ? $theRange['timeframe-2'] : 'xx';
            $newDeadline = $delivery_range_1." - ".$delivery_range_2;
        } else if (strpos(" ".$theData, "latest")) {
            $newDeadline = str_replace("latest__", "Senest kl. ", $theData);
        }
        $this->appends['delivery_deadline'] = $newDeadline;
    }
    public function getDeliveryDeadlineAttribute() { return $this->appends['delivery_deadline']; }

    // Get the shipping infos for the order.
    public function shippingInfos(): HasMany
    {
        return $this->hasMany(Shipping::class, 'post_id');
    }

    // Get a summary of the order in an array
    public function getSummary()
    {
        return array(
            "orderId" => $this->order_id,
            "totalSale" => $this->total_sales,
            "destinationAdr" => $this->appends['adr']." ".$this->appends['street_nr'],
            "destinationArea" => $this->appends['postcode']." ".$this->appends['city'],
            "deliveryDeadline" => $this->appends['delivery_deadline']
        );
    }

    public function getComplete()
    {
        $summary = $this->getSummary();
        $theRest = array(
            'dateCreated' => $this->date_created,
            'numItemsSold' => $this->num_items_sold,
            'status' => $this->status,
            'customerId' => $this->customer_id,
            'datePaid' => $this->date_paid,
            'dateCompleted' => $this->date_completed,
            'theHelperIs' => $this->appends['theHelperIs']
        );
        return array_merge($summary, $theRest);
    }
}
