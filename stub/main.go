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

var (
	cacheSince = time.Now().Format(http.TimeFormat)
	cacheUntil = time.Now().AddDate(60, 0, 0).Format(http.TimeFormat)
)

func HelloServer(w http.ResponseWriter, r *http.Request) {
    if r.URL.Path[1:] == "timeout" {
	    time.Sleep(65 * time.Second)
    }
    if r.URL.Path[1:] == "slow" {
        time.Sleep(2 * time.Second)
    }
	w.Header().Set("Cache-Control", "max-age:290304000, public")
    w.Header().Set("Last-Modified", cacheSince)
    w.Header().Set("Expires", cacheUntil)
	fmt.Fprintf(w, "Hello, %s!", r.URL.Path[1:])
}
