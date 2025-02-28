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
        [translations.user, translations.facilitator, translations.admin],
    );
}

export default { initAccountTypesChart };
