# php-router

# TODO:

- add custom exception handling

## what is this?

Well a barebones router of course. This component should be able to grab a request, map it to an execution context, extract whatever parameters are specified for that context and execute them.

## why?

Because.

# do you know XXXX, YYYY and ZZZZ exist?

Maybe. Probably.

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
- something to build parameter extractors and the parameters extractors themselves.
- something to build output transformers and the output transformers themselves.

These almost always come in pairs of "a factory" and "stuff the factory builds". These are supposed to be evil multi-factories that you pass a key in and they return an object that satisfies the interface. The implementor controls these keys and what kind of objects can be built with them. Most factories can return null to indicate that nothing with that key is to be built (and probably crash later).

A closer look follows:

### request:

You need to provide a factory that can build a request object that satisfies the interface in the router. Nothing fancy, URI, http method, headers, query string, body, the works.

### input transformers:

You may provide a factory of objects that can take a request and turn them into another request (decoding the body, for example, mapping XML to json or vice-versa...).

### authorizers:

You may provide a factory of objects that can answer if a request is authorized. These can be later chained to provide complex auth processes.

### path mapper:

You must provide a path mapper and uri transformer. The uri transformer takes a uri and manipulates it (say, you want to remove leading directories from the path part of the URI because you are lazy and work under localhost/myproject). The path mapper takes that manipulated URI and the HTTP method and makes it correspond to a call to something, a controller. Each of these calls can have their own authorizers, input transformers, output transformers and parameter extractors.

### controllers:

You must provide something to do the work. The only things that the router asks are:

- it must be constructible.
- the methods you want to use to do the work must be callable.
- the methods you want to use must return a value that loosely maps to an http response.

### parameter extractors:

You may provide a factory to build objects that can take a request and extract parameters (like function parameters) for your controllers. These can be extracted from json bodies, url encoded requests, query strings and even the uri itself. The class parameter_maker is full of helpers for this job. The arguments for your controller methods must be somehow mapped (by the mapper) so the router knows how to forward them. In practice this is easy.

### output transformers

Once your controller returns that loose response, you must provide a factory of objects that can take these loose responses and turn them into real http_responses.

## the example

The example is yes another contact manager yay. Should be easy enough to setup given that the proper permissions are given to the data directory.

