<script>
import { Line, mixins } from 'vue-chartjs'

const { reactiveProp } = mixins

const defaultOptions = {
    responsive: true,
    maintainAspectRatio: false,
    layout: {
        padding: {
            top: 1,
            bottom: 1
        }
    },
    hover: {
        intersect: false,
        mode: 'label'
    },
    tooltips: {
        intersect: false,
        mode: 'label',
        displayColors: false
    },
    legend: {
        display: false
    },
    elements: {
        line: {
            borderWidth: 2,
            cubicInterpolationMode: 'monotone'
        },
        point: {
            radius: 0,
            hoverRadius: 4,
            pointStyle: 'round'
        }
    },
    scales: {
        yAxes: [
            {
                display: false,
                ticks: {
                    suggestedMin: 0
                }
            }
        ],
        xAxes: [
            {
                display: false
            }
        ]
    },
    animation: false
}

export default {
    name: 'Sparkline',

    extends: Line,

    mixins: [reactiveProp],

    props: ['chartData', 'options'],

    mounted () {
        this.renderChart(this.chartData, Object.assign({}, this.options, defaultOptions))
    },
}
</script>
