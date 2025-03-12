const initChart = (id, title, type, dataLabel, data, labels, fontFamily, baseOptions) => {
    let canvas = document.getElementById(id).getContext("2d");

    let chartData;
    if(Array.isArray(dataLabel)) {
        chartData = {
            labels: labels,
            datasets: []
        };
        data.forEach((dataset, index) => {
            chartData.datasets.push({
                label: dataLabel[index],
                data: dataset
            });
        });
    } else {
        chartData = {
            labels: labels,
            datasets: [{
                label: dataLabel,
                data: data
            }]
        };
    }

    baseOptions = baseOptions || {};

    return new Chart(canvas, {
        type: type,
        data: chartData,
        options: {
            ...baseOptions,
            responsive: true,
            aspectRatio: 1,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: title,
                    font: {
                        family: fontFamily || "OpenSans"
                    }
                },
                legend: {
                    display: true,
                    position: "bottom",
                    labels: {
                        font: {
                            family: fontFamily || "OpenSans"
                        }
                    }
                }
            }
        }
    });
}

export const initAccountTypesChart = (translations, data) => {
    initChart(
        "statistics-account-types",
        translations.title,
        "doughnut",
        translations.dataLabel,
        data,
        [translations.user, translations.facilitator, translations.admin]
    );
}

export const initUserTypesChart = (translations, data) => {
    initChart(
        "statistics-user-types",
        translations.title,
        "doughnut",
        translations.dataLabel,
        data,
        [translations.participant, translations.tutor]
    );
}

export const initGroupsChart = (translations, data, customGroups) => {
    let chartData = [ data.default ];
    let chartLabels = [ translations.defaultGroup ];

    for(const groupId in customGroups) {
        if(data.customData[groupId] !== undefined) {
            chartData.push(data.customData[groupId]);
        } else {
            chartData.push(0);
        }
        chartLabels.push(customGroups[groupId]);
    }

    initChart(
        "statistics-groups",
        translations.title,
        "doughnut",
        translations.dataLabel,
        chartData,
        chartLabels
    );
}

export const initChoicesChart = (translations, data) => {
    initChart(
        "statistics-choices",
        translations.title,
        "doughnut",
        translations.dataLabel,
        data,
        [translations.complete, translations.incomplete, translations.missing]
    );
}

export const initChoicesByGroupChart = (translations, data, customGroups) => {
    let flippedChartData = [ data.default ];
    let chartLabels = [ translations.defaultGroup ];

    for(const groupId in customGroups) {
        if(data.customData[groupId] !== undefined) {
            flippedChartData.push(data.customData[groupId]);
        } else {
            flippedChartData.push({
                complete: 0,
                incomplete: 0,
                missing: 0
            });
        }
        chartLabels.push(customGroups[groupId]);
    }

    let chartData = [];
    for(const key in flippedChartData[0]) {
        let dataset = [];
        flippedChartData.forEach((data) => {
            dataset.push(data[key]);
        });
        chartData.push(dataset);
    }

    initChart(
        "statistics-choices-by-group",
        translations.title,
        "bar",
        [translations.complete, translations.incomplete, translations.missing],
        chartData,
        chartLabels,
        "OpenSans",
        {
            scales: {
                x: {
                    stacked: true
                },
                y: {
                    stacked: true
                }
            }
        }
    );
}

export const initCoursesChart = (translations, data) => {
    initChart(
        "statistics-courses",
        translations.title,
        "doughnut",
        translations.dataLabel,
        data,
        [translations.user, translations.facilitator, translations.cancelled]
    );
}

export const initCoursesByGroupChart = (translations, data, customGroups) => {
    let chartData = [ data.default ];
    let chartLabels = [ translations.defaultGroup ];

    for(const groupId in customGroups) {
        if(data.customData[groupId] !== undefined) {
            chartData.push(data.customData[groupId]);
        } else {
            chartData.push(0);
        }
        chartLabels.push(customGroups[groupId]);
    }

    initChart(
        "statistics-courses-by-group",
        translations.title,
        "doughnut",
        translations.dataLabel,
        chartData,
        chartLabels
    );
}

export const initPlacesChart = (translations, data) => {
    initChart(
        "statistics-places",
        translations.title,
        "doughnut",
        translations.dataLabel,
        data,
        [translations.available, translations.occupied, translations.cancelled]
    );
}

export const initPlacesByGroupChart = (translations, data, customGroups) => {
    let flippedChartData = [ data.default ];
    let chartLabels = [ translations.defaultGroup ];

    for(const groupId in customGroups) {
        if(data.customData[groupId] !== undefined) {
            flippedChartData.push(data.customData[groupId]);
        } else {
            flippedChartData.push({
                complete: 0,
                incomplete: 0,
                missing: 0
            });
        }
        chartLabels.push(customGroups[groupId]);
    }

    let chartData = [];
    for(const key in flippedChartData[0]) {
        let dataset = [];
        flippedChartData.forEach((data) => {
            dataset.push(data[key]);
        });
        chartData.push(dataset);
    }

    initChart(
        "statistics-places-by-group",
        translations.title,
        "bar",
        [translations.available, translations.occupied, translations.cancelled],
        chartData,
        chartLabels,
        "OpenSans",
        {
            scales: {
                x: {
                    stacked: true
                },
                y: {
                    stacked: true
                }
            }
        }
    );
}

export const initAssignmentsChart = (translations, data) => {
    initChart(
        "statistics-assignments",
        translations.title,
        "doughnut",
        translations.dataLabel,
        data,
        [translations.assigned, translations.notAssigned, translations.noChoice]
    );
}

export const initAssignmentsByGroupChart = (translations, data, customGroups) => {
    let flippedChartData = [ data.default ];
    let chartLabels = [ translations.defaultGroup ];

    for(const groupId in customGroups) {
        if(data.customData[groupId] !== undefined) {
            flippedChartData.push(data.customData[groupId]);
        } else {
            flippedChartData.push({
                assigned: 0,
                notAssigned: 0,
                noChoice: 0
            });
        }
        chartLabels.push(customGroups[groupId]);
    }

    let chartData = [];
    for(const key in flippedChartData[0]) {
        let dataset = [];
        flippedChartData.forEach((data) => {
            dataset.push(data[key]);
        });
        chartData.push(dataset);
    }

    initChart(
        "statistics-assignments-by-group",
        translations.title,
        "bar",
        [translations.assigned, translations.notAssigned, translations.noChoice],
        chartData,
        chartLabels,
        "OpenSans",
        {
            scales: {
                x: {
                    stacked: true
                },
                y: {
                    stacked: true
                }
            }
        }
    );
}

export default {
    initAccountTypesChart,
    initUserTypesChart,
    initGroupsChart,
    initChoicesChart,
    initChoicesByGroupChart,
    initCoursesChart,
    initCoursesByGroupChart,
    initPlacesChart,
    initPlacesByGroupChart,
    initAssignmentsChart,
    initAssignmentsByGroupChart
};
