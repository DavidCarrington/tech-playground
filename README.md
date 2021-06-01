# K6

This is the theoretical command to run:

`K6_STATSD_ENABLE_TAGS=true k6 run --out statsd k6/test.js`

However, volume is a concern, so you likely also need to follow
instructions at https://k6.io/docs/misc/fine-tuning-os/

Specifically within WSL2:

```
sudo su
ulimit -n 5000
K6_STATSD_ENABLE_TAGS=true k6 run --out statsd k6/test.js
```