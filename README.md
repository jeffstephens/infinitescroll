# Accessible Infinite Scroll

A simple demonstration of how to implement an infinite scroll autoload that preserves back button and address bar functionality.
* [Live Demo](http://jeffastephens.com/infinitescroll/)
* [Medium Post](https://medium.com/design-ux/51b224e42926)

## Motivation
Sites all over the web these days have implemented views that will load content in as long as you keep scrolling. It's on Facebook, Youtube - everywhere. Yet despite a very simple technique to avoid it, **the overwhelming majority of these sites break fundamental browser usabilty**. If you accidentally click a link or refresh the page, you have to scroll or click your way all the way back through the content to get back to where you left off. That's incredibly annoying.

This annoyance prompted me to write [a post on Medium](https://medium.com/design-ux/51b224e42926) complaining, mainly because it's so easy to get around!

## How it Works
Javascript offers an easy way to save page state with a technique that's been around since a year or two after Gmail's inception, if I recall correctly. By reading and modifying `window.location.hash`, Javascript can have an easy and portable insight into page state. When you combine this with a backend that sends a unique identifier with each batch of content, it's incredibly easy to use that state information to make a request to the backend that lets the user resume where they left off immediately.

My example in this repository uses a unique ID provided by the Medium RSS feed I scrape, but it's easy to generate this without even revealing database IDs if you care about that sort of thing - just `md5` something that's unique, like the timestamp concatenated with the title, for example, and you should be able to easily generate unique IDs for the Javascript state information.

When my backend receives an identifier, it will return all the posts up to the one matching that identifier instead of its normal limit of 3.

## Live Demo
I'm running the code in this repository [on my live demo site](http://jeffastephens.com/infinitescroll/). You should be able to get an idea of how it works by loading in a page or two and watching the address bar.
