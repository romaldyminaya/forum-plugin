# Forum plugin

This plugin adds a simple embeddable forum to [OctoberCMS](http://octobercms.com).

## Configuration

The forum does not require immediate configuation to operate. However the following options are available.

* Forum categories (Channels) can be managed via the System > Channels menu.
* Forum members can be managed via the User menu.

## Displaying a list of channels

The plugin includes a component channelList that should be used as the main page for your forum. Add the component to your page and render it with the component tag:

```php
{% component 'channelList' %}
```

You should tell this component about the other forum pages.

* **channelPage** - the page used for viewing an individual channel's topics.
* **topicPage** - the page used for viewing a discussion topic and posts.
* **memberPage** - the page used for viewing a forum user.
