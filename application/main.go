package main

import (
	"fmt"
	"net/http"
	"os"
	"time"

	"github.com/newrelic/go-agent/v3/newrelic"
)

func main() {
	licence := os.Getenv("NEW_RELIC_LICENSE_KEY")
	app, _ := newrelic.NewApplication(
		newrelic.ConfigAppName("go-stub"),
		newrelic.ConfigLicense(licence),
		newrelic.ConfigDistributedTracerEnabled(true),
	)
	http.HandleFunc(newrelic.WrapHandleFunc(app, "/", HelloServer))
	http.ListenAndServe(":8080", nil)
}

func HelloServer(w http.ResponseWriter, r *http.Request) {
	time.Sleep(2 * time.Second)
	fmt.Fprintf(w, "Hello, %s!", r.URL.Path[1:])
}
