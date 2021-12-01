# php-router

## what is this?

Well a barebones router of course. This component should be able to grab a request, map it to an execution context, extract whatever parameters are specified for that context and execute them.

## why?

Because.

## do you know XXXX, YYYY and ZZZZ exist?

Maybe. Probably. What does it matter to you?

## why would I use this and not XXXX, YYYY and ZZZZ?

Your guess, not mine.

I can tell you my reasons: I really don't enjoy using third party components for personal projects and this is aimed at personal stuff, so there.

## how does it work?

With your engagement: there are, of course, a few contracts in place, like the way in which these execution enviroments (controllers) must return a very particular class and how you must provide for most stuff that falls outside the skeleton.

The kind of things you are supposed to provide are:

- something to build requests and a request-compatible component.
- something to build request input transformers, and transformers themselves (if need be)
- something to build authorizers, and authorizers if need be.
- something to build a mapper from a request to a controller (a path mapper) and path_mapper themselves.
- something to build controllers
- something to build parameter extractors and the parameter extractors themselves.
- something to build parameter mappers and the parameter mappers themselves.
- something to build output transformers and the output transformers themselves.
- something to build error handlers and the error handlers themselves.

These almost always come in pairs of "a factory" and "stuff the factory builds". These are supposed to be evil multi-factories that you pass a key in and they return an object that satisfies the interface. The implementor controls these keys and what kind of objects can be built with them. Most factories can return null to indicate that nothing with that key is to be built (and probably throw later).

A closer look follows:

### request:

You need to provide a factory that can build a request object that satisfies the interface in the router. Nothing fancy, URI, http method, headers, query string, body, the works.

### input transformers:

You may provide a factory of objects that can take a request and turn them into another request (decoding the body, for example, mapping XML to json or vice-versa...).

### authorizers:

You may provide a factory of objects that can answer if a request is authorized. These can be later chained to provide complex auth processes.

### path mapper:

You must provide a path mapper and uri transformer. The uri transformer takes a uri and manipulates it (say, you want to remove leading directories from the path part of the URI because you are lazy and work under localhost/myproject). The path mapper takes that manipulated URI and the HTTP method and makes it correspond to a call to something, a controller. Each of these calls can have their own authorizers, input transformers, output transformers, parameter extractors...

This component can really benefit of a config file, or a precompiled approach.

### controllers:

You must provide something to do the work. The only things that the router asks are:

- it must be constructible.
- the methods you want to use to do the work must be callable.
- the methods you want to use must return a value that loosely maps to an http response.

### parameter extractors:

You may provide a factory to build objects that can take a request and extract parameters (like function parameters) for your controllers. These can be extracted from json bodies, url encoded requests, query strings and even the uri itself. The class argument_maker is full of helpers for this job. The parameter for your controller methods must be somehow mapped (by the mapper) so the router knows how to forward them. In practice this is easy.

### parameter mappers

It may happen that you use a file to specify the mappings that the path_mapper must carry out. In that case, you will have declared a bunch of parameters there. A parameter mapper takes the name declared there and converts it into the name specified in the controller code itself, in case there are specific conventions in place.

### output transformers

Once your controller returns that loose response, you must provide a factory of objects that can take these loose responses and turn them into real http_responses.

### error handlers

The router will always have a default error handler. When the router has been built, you can add additional handlers through a call to "add_exception_handler". Routes can also define their own specific handlers.

There's a strong guarantee that every exception or error thrown inside router::route will be caught automatically by the router. When caught, the handlers attempt to handle the exception in this order: route specific, manually added handlers and the default handler (that will always handle everything).

Handlers will receive an Error or Exception and can test them with instanceof to determine specificically what happened.

## a word about default parameters

The router will attempt to locate arguments in the request for all your method parameters, even if you define default values for them, so any missing parameters (as defined by the parameters class) will throw.

## the example

The example is yes another contact manager yay. Should be easy enough to setup given that the proper permissions are given to the data directory.

### setting it up: dependencies

Ok, let's start by checking the example/src/external/what-goes-here file. Just follow the instructions there: clone the repositories, move the files as described and you're done.

### setting it up: paths

Let's go to example/index.php and locate where "\rimplementation\uri_transformer" is built. This class wants a string passed to its constructor and the string means "what do I substract from the path part of my uri so I can get clean paths".

To be honest, I am just lazy. I run this stuff at localhost so my complete path may be something like /path/to/php-router/example... When I instruct my browser to do "blah" there I end up with "http://localhost/php-router/example/blah" and I only want the blah part. That's the job of the uri_transformer:

- splits the uri into parts and takes only the path "php-router/example/blah".
- removes whatever we pass into the constructor.
- we got "blah".

That said, set it up so you can get "blah" to.

### setting it up: permissions

The example wants the web server to write in the data directory. Do what you must.

### done

That would be all. You should be all set.

### why don't you do regular expressions for the path_mapper and instead rely on your own component?

Can't be bothered to.

## how do I use this into my own project?

Are you going to use this? ok, well... Plase, just study the example. No need to really really study the router, but study the example itself, see what's what and what it does, realise that most factory interfaces can be implemented with something that goes return new $classname($mydependencycontainer) and that you can actually use a single class to implement them all, dirty stile.

Just one thing, the example is not production-quality at all. It is quick, dirty and takes a lot of shortcuts. Please, don't be like that.


