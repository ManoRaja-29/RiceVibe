<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach($urls as [$loc, $mod])
    <url><loc>{{ $loc }}</loc>@if($mod)<lastmod>{{ $mod->toAtomString() }}</lastmod>@endif</url>
@endforeach
</urlset>
