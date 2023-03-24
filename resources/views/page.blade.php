<div>
    <h1>
        {{ $displayPage->title }}
    </h1>
    
    <p>
        <strong>Posted by: </strong>{{ $displayPage->author->display_name }}
    </p>

    <p>
        {!! $displayPage->content !!}
    </p>

</div>