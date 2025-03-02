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

export const initGroupsChart = (translations, data) => {

    let chartData = [ data.default ];
    let chartLabels = [ translations.defaultGroup ];

    for(const groupId in data.customLabels) {
        if(data.customData[groupId] !== undefined) {
            chartData.push(data.customData[groupId]);
        } else {
            chartData.push(0);
        }
        chartLabels.push(data.customLabels[groupId]);
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

export default {
    initAccountTypesChart,
    initUserTypesChart,
    initGroupsChart
};
