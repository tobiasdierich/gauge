function getChartData(endpoint, metric, dateRange) {
    return fetchMetric(endpoint, {
        metric,
        options: getMetricOptionsForDateRange(dateRange)
    })
        .then(response => response.data)
}

function getMetricOptionsForDateRange(dateRange) {
    if (dateRange === 'live') {
        return {
            unit: 'second',
            unit_step: 10,
            start_date: '-5 minutes'
        }
    }

    const unitSteps = {
        'hour': {
            unit: 'minute',
            unit_step: 2
        },
        'day': {
            unit: 'hour',
            unit_step: 1
        },
        'week': {
            unit: 'day',
            unit_step: 1
        }
    }

    return Object.assign(unitSteps[dateRange], { start_date: `-1 ${dateRange}` })
}

function fetchMetric (endpoint, data) {
    return fetch(endpoint, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json;charset=utf-8'
        },
        body: JSON.stringify(data)
    })
        .then(response => response.json())
}

export { getChartData }
