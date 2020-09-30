import Vue from 'vue'
import Chart from './components/Chart'

Vue.component('chart', Chart)

new Vue({
    el: '#requestsChart',
    data: {
        title: 'Requests per Second',
        endpoint: window.Gauge.path + '/gauge-api/requests/metrics'
    }
})

new Vue({
    el: '#queriesChart',
    data: {
        title: 'Queries per Second',
        endpoint: window.Gauge.path + '/gauge-api/queries/metrics'
    }
})
