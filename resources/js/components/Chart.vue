<template>
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="p-4 flex justify-between items-end">
            <h2 class="text-sm font-semibold text-gray-600">{{ title }}</h2>

            <div class="flex space-x-4">
                <a
                    class="text-xs"
                    href="#"
                    :class="[ isActiveRange('week') ? 'font-black text-gray-600' : 'font-bold text-gray-500 hover:text-gray-600']"
                    @click="handleChangeDateRange('week')"
                >
                    1w
                </a>
                <a
                    class="text-xs"
                    href="#"
                    :class="[ isActiveRange('day') ? 'font-black text-gray-600' : 'font-bold text-gray-500 hover:text-gray-600']"
                    @click="handleChangeDateRange('day')"
                >
                    1d
                </a>
                <a
                    class="text-xs"
                    href="#"
                    :class="[ isActiveRange('hour') ? 'font-black text-gray-600' : 'font-bold text-gray-500 hover:text-gray-600']"
                    @click="handleChangeDateRange('hour')"
                >
                    1h
                </a>
                <a
                    class="flex items-baseline text-xs"
                    href="#"
                    :class="[ isActiveRange('live') ? 'font-black text-gray-600' : 'font-bold text-gray-500 hover:text-gray-600']"
                    @click="handleChangeDateRange('live')"
                >
                    Live

                    <div ref="dotPulse" class="ml-2 h-2 w-2 rounded-full" :class="[ isActiveRange('live') ? 'bg-red-600' : 'bg-gray-600']"></div>
                </a>
            </div>
        </div>

        <div class="w-full relative">
            <sparkline :chart-data="chartData" :width="null" :height="null"/>
        </div>
    </div>
</template>

<script>
import { getChartData } from '../api/metrics'
import Sparkline from './Sparkline.vue'

export default {
    props: {
        title: {
            type: String,
            required: true
        },
        endpoint: {
            type: String,
            required: true
        }
    },

    data () {
        return {
            dateRange: 'live',
            metricData: [],
            dataRefresher: null
        }
    },

    computed: {
        chartData () {
            return {
                labels: this.chartLabels,
                datasets: [
                    {
                        data: this.metricData.map(data => data.value),
                        borderColor: '#5a67d8',
                        backgroundColor: '#ebf4ff',
                        pointBorderColor: '#5a67d8',
                        pointBackgroundColor: '#ebf4ff',
                        fill: true
                    }
                ]
            }
        },

        chartLabels () {
            return this.metricData.map(data => {
                const date = new Date(data.startDate)

                if (this.dateRange === 'week') {
                    return date.toLocaleDateString('en-US')
                }

                return date.toLocaleTimeString('en-US')
            })
        }
    },

    methods: {
        handleChangeDateRange (range) {
            if (this.dateRange !== 'live' && range === 'live') {
                this.startPeriodicDataRefresh()
            }

            if (this.dateRange === 'live' && range !== 'live') {
                this.stopPeriodicDataRefresh()
            }

            this.dateRange = range

            this.refreshData()
        },

        isActiveRange (range) {
            return this.dateRange === range
        },

        refreshData () {
            return getChartData(this.endpoint, 'throughput', this.dateRange)
                .then(metricData => {
                    this.metricData = metricData
                })
        },

        refreshDataAndPulse () {
            this.refreshData().then(this.pulse)
        },

        pulse () {
            this.$refs.dotPulse.classList.add('dot-pulse')

            setTimeout(() => { this.$refs.dotPulse.classList.remove('dot-pulse') }, 2000)
        },

        startPeriodicDataRefresh () {
            this.dataRefresher = setInterval(this.refreshDataAndPulse, 5000)
        },

        stopPeriodicDataRefresh () {
            clearInterval(this.dataRefresher)

            this.dataRefresher = null
        }
    },

    created () {
        this.refreshData()

        this.startPeriodicDataRefresh()
    },

    components: {
        Sparkline
    }
}
</script>
