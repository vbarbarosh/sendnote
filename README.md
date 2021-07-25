Send quick notes right to an email. Uses GMail as an email gateway.

## Building

    bin/build

## Configuration

    cat > .env << EOF
    GMAIL_EMAIL=user@gmail.com
    GMAIL_PASSWORD=123123123123123
    EOF

## Running locally

    xdg-open http://127.0.0.1:3000
    docker run --env-file=.env --rm -p 3000:80 sendnote

    or

    xdg-open http://127.0.0.1:3000
    docker-compose up

## References

* [Send email on testing docker container with php and sendmail](https://stackoverflow.com/a/63977888/1478566)
