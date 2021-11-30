# php-router

# TODO:

- add custom exception handling

# what is this?

Well a barebones router of course. This component should be able to grab a request, map it to an execution context, extract whatever parameters are specified for that context and execute them.

There are, of course, a few contracts in place, like the way in which these execution enviroments (controllers) must return a very particular class and how you must provide for most stuff that falls outside the skeleton. 

The kind of things you are supposed to provide are:

- something to build requests and a request-compatible component.
- something to build authorizers, and authorizers if need be.
- something to build controllers
- something to build request input transformers, and transformers themselves (if need be)
- something to build parameter extractors and the parameters extractors themselves.
- something to build 

# why

Because.

# do you know XXXX, YYYY and ZZZZ exist?

Maybe. Probably. In any case, I really don't enjoy using third party components for personal projects and this is aimed at personal stuff, so there.

# the example

The example is yes another contact manager yay. Should be easy enough to setup given that the proper permissions are given to the data directory.

