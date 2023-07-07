<link rel="stylesheet" href="<?php echo asset('css/styles.css')?>" type="text/css">

<h1 class="page-header">Rick and Morty Characters</h1>

<div class="search-container">
    <form id="search-form" action="/characterslist/filter">
        <input type="search" id="name" name="name" placeholder="Character Name" value="<?php if(isset($data['filters']['name'])): echo $data['filters']['name'];endif; ?>">
        <label for="filter-status">Status:</label>
        <select id="filter-status" name="status" aria-controls="filter-characters" data-sort="true">
            <option value=""></option>
            <option value="alive" >Alive</option>
            <option value="dead" >Dead</option>
            <option value="unknown" >Unknown</option>
        </select>
        <label for="filter-species">Species:</label>
        <select id="filter-species" name="species" aria-controls="filter-characters" data-sort="true">
            <option value=""></option>
            <option value="human" >Human</option>
            <option value="alien" >Alien</option>
            <option value="humanoid" >Humanoid</option>
            <option value="animal" >Animal</option>
            <option value="unknown" >Unknown</option>
        </select>
        <label for="filter-gender">Gender:</label>
        <select id="filter-gender" name="gender" aria-controls="filter-characters" data-sort="true">
            <option value=""></option>
            <option value="female" >Female</option>
            <option value="male" >Male</option>
            <option value="unknown" >Unknown</option>
        </select>
        <input type="submit" value="Filter">
        @if(isset($data['filters']))
            <input type="button" onclick="window.location = '/characterslist'" value="Clear Search" />
        @endif
    </form>
</div>
<div class="character-container">
    @if(isset($data['results']))
        @foreach ($data['results'] as $item)
            <article class="character">
                <div><img src="{{ $item['image'] }}" alt="{{ $item['name'] }}"></div>
                <div class="details">
                    <h2>{{ $item['name'] }}</h2>
                    <p><label>Status:</label> {{ $item['status'] }}</p>
                    <p><label>Species:</label> {{ $item['species'] }}</p>
                    <p><label>Gender:</label> {{ $item['gender'] }}</p>
                    <p><label>Origin:</label> {{ $item['origin']['name'] }}</p>
                    <p><label>Episode first appeared:</label>
                        {{ $item['episode']['name'] }}
                    </p>
                </div>
            </article>
        @endforeach
    @else
    <h2>No results to show</h2>
    @endif
</div>
<div class="page-controls">
    @if (isset($data['info']) && $data['info']['prev'] != '')
    <a href="<?= $data['prevPageUrl'] ?>" class="previous">&laquo; Previous</a>
    @endif

    @if (isset($data['info']) && $data['info']['next'] != '')
    <a href="<?= $data['nextPageUrl'] ?>" class="next">Next &raquo;</a>
    @endif
</div>
<script>
    let urlParams = new URLSearchParams(window.location.search);
    let queryString = '';
    let filters = ['status','species','gender'];
    let i = 0;

    while(i < filters.length){
        queryString = urlParams.get(filters[i]);
        if(queryString !== '') {
            document.getElementById("filter-" + filters[i]).querySelector("option[value='" + queryString + "']").selected = true;
        }
        i++;
    }
</script>
