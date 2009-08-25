<?
    $upcoming_id = $_GET['upcoming_id'];
    
    if (!is_numeric($upcoming_id)) {
        echo "Invalid request: parameter upcoming_id must be numeric\n";
        echo 'Example: <a href="http://www.phusikos.com/event.php?upcoming_id=2376485">http://www.phusikos.com/event.php?upcoming_id=2376485</a>.';
    }
    
    // Fetched parsed microformats JSON from Optimus
    $url = "http://upcoming.yahoo.com/event/$upcoming_id/";
    $optimus_url = 'http://microformatique.com/optimus/?uri=' . urlencode($url) . '&format=json&function=&filter=hcalendar';
    $json = file_get_contents($optimus_url);
    $event = json_decode($json);
    
    if (!$event) {
        echo "Invalid request: $upcoming_id - $optimus_url\n";
        exit;
    }
    
    // Format start and end dates
    date_default_timezone_set('America/Los_Angeles');
    $start_date = date('Y-m-d', strtotime($event->hcalendar->dtstart));
    $start_time = date('H:i', strtotime($event->hcalendar->dtstart));
    $end_time = $event->hcalendar->dtend ? date('H:i', strtotime($event->hcalendar->dtend)) : null;
    
    // Format the event tag
    $tag = 'microformats-dinner-' . date('Y-m-d', strtotime($event->hcalendar->dtstart));

    // Fix address
    $adr = $event->hcalendar->location->adr;
    $adr = is_array($adr) ? $adr[0] : $adr;

    // Format location properties
    $fn = htmlspecialchars($event->hcalendar->location->fn);
    $street_address = is_array($adr->{'street-address'}) ? htmlspecialchars($adr->{'street-address'}[0])
                                                         : htmlspecialchars($adr->{'street-address'});
    $locality = $adr->locality == 'San Francisco Bay Area' ? 'San Francisco' : htmlspecialchars($adr->locality);
    $region = $adr->region == 'California' ? 'CA' : htmlspecialchars($adr->region);
    $postal_code = htmlspecialchars($adr->{'postal-code'});
    $country_name = $adr->country_name ? htmlspecialchars($adr->country_name) : 'USA';
    
    header('Content-type: text/plain');
?>
<entry-title>Microformats Weekly Dinner, San Francisco</entry-title>
__TOC__
One of several microformats [[weekly-meetup]] [[events]].
 
<div class="event-page vevent">
== Details ==
;When
:<span class="dtstart"><span class="value"><?= $start_date ?></span> from <span class="value"><?= $start_time ?></span></span><? if ($end_time): ?> to <span class="dtend"><span class="value"><?= $end_time ?></span></span><? endif; ?>

;Where
:<span class="location vcard"><span class="fn org"><?= $fn ?></span>, <span class="adr"><span class="street-address"><?= $street_address ?></span>, <span class="locality"><?= $locality ?></span>, <span class="region"><?= $region ?></span> <span class="postal-code"><?= $postal_code ?></span> <span class="country-name"><?= $country_name ?></span></span><?= $location_url ? '<span class="url">' . $location_url . '</span>' : '' ?></span>
;What
:<span class="summary">Microformats Weekly Meetup Dinner, San Francisco</span>
;Web
:<span class="url"><?= $event->from ?></span>
 
'''[http://feeds.technorati.com/events/referer Add this event to your calendar]''' http://www.boogdesign.com/images/buttons/microformat_hcalendar.png
 
== Weekly Meetup ==
<div class="description">The microformats community has grown and stabilized over the past few years, news of adoptions, new ideas and challenges come up frequently enough that there are no shortage of new topics to discuss on a weekly basis.
 
Come along, meet up with the microformats community in San Francisco 
 
In another city? Check out [[weekly-meetup#Other_Cities|Weekly Meetup: Other Cities]] and help organize one in your own city!</div>
 
== Tags ==
Use the following tags on related content (blog posts, photos, [http://twitter.com tweets]):

tags:
<kbd class="tags" style="display:block">
<span class="category">'''microformats-dinner'''</span> 
<span class="category">microformats-meetup</span> 
<span class="category">microformats</span> 
<span class="category">san-francisco</span> 
<span class="category">''<?= $tag ?>''</span>
<span class="category">''upcoming:event=<?= $upcoming_id ?>''</span>
</kbd>
 
If you use Twitter, mention ''@microformats dinner''' in tweets about the event, and track them on [http://search.twitter.com/search?q=microformats+dinner Twitter Search].
 
== Attendees ==
Add yourself alphabetically sorted by family name if you plan on attending or attended this event.
 
* [[User:MatthewLevine|Matthew Levine]]
* ...
 
== Notes ==
 
Topics Discussed:
 
* ...
 
== Photographs ==
 
* Search for photographs from this event on Flickr: [http://flickr.com/photos/tags/<?= $tag ?> Photographs tagged <?= $tag ?>] or for [http://flickr.com/photos/tags/microformats-dinner all photographs from microformats dinners].
 
''Add a photograph from this event here''.
 
== Articles and Blog Posts ==
Articles and blog posts following up on the meetup. Add a link to your post in the list below:
 
* ...

Also, find posts on this meetup on [http://blogsearch.google.com/blogsearch?q=<?= $tag ?> Google Blog Search] or [http://technorati.com/search/<?= $tag ?> Technorati].
 
</div> <!-- End of @vevent -->
 
== Related Pages==
{{events-related-pages}}
