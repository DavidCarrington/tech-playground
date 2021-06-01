FROM golang:1.15.11-buster

ENV APP_NAME stub
ENV PORT 8080

COPY . /go/src/${APP_NAME}
WORKDIR /go/src/${APP_NAME}

RUN go get ./
RUN go get github.com/newrelic/go-agent
RUN go build -o ${APP_NAME}

CMD ./${APP_NAME}

EXPOSE ${PORT}
