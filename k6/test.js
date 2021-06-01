import http from 'k6/http';

export let options = {
  discardResponseBodies: true,
  scenarios: {
    contacts: {
      executor: 'ramping-arrival-rate',
      startRate: 1,
      timeUnit: '1s',
      preAllocatedVUs: 5000,
      maxVUs: 10000,
      stages: [
        { target: 50, duration: '1m' },
        { target: 0, duration: '30s' },
      ],
    },
  },
};

export default function () {
  http.get('http://localhost:8003/John');
}
