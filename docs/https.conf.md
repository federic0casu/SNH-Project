# How to HTTPS: a brief tutorial to bootstrap https locally

Basically, we'll simulate a CA by means of ![https://github.com/FiloSottile/mkcert](`mkcert`).


## `mkcert`

`mkcert` is a simple tool for making locally-trusted development certificates. Using certificates from real certificate authorities (CAs) for development can be dangerous or impossible (for hosts like `localhost` or `127.0.0.1`), but self-signed certificates cause trust errors.

`mkcert` automatically creates and installs a local CA in the system root store, and generates locally-trusted certificates. `mkcert` does not automatically configure servers to use the certificates, though, that's up to you.


## Installation

First thing first: we need `certutil`: it is part of `libnss3-tools` package. In a Debian-based distro, we can install `libnss3-tools` through the default pack-manager:
```sh
$ sudo apt install libnss3-tools
```
Then, we can proceed installing ![https://docs.brew.sh/Homebrew-on-Linux](`Homebrew on Linux`).
```sh
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```
Then we can install `mkcert`:
```sh
brew install mkcert
```
Last, but not least, we need to install a local CA:
```sh
mkcert -install
```
