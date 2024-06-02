//已经弃用
const colors = {
    "red": {
        backgroundColor: 'rgba(255, 99, 132, 0.2)',
        borderColor: 'rgb(255, 99, 132)',
        pointBackgroundColor: 'rgb(255, 99, 132)',
        pointBorderColor: '#fff',
        pointHoverBackgroundColor: '#fff',
        pointHoverBorderColor: 'rgb(255, 99, 132)'
    },
    "blue": {
        backgroundColor: 'rgba(54, 162, 235, 0.2)',
        borderColor: 'rgb(54, 162, 235)',
        pointBackgroundColor: 'rgb(54, 162, 235)',
        pointBorderColor: '#fff',
        pointHoverBackgroundColor: '#fff',
        pointHoverBorderColor: 'rgb(54, 162, 235)'
    },
    "green":{
        backgroundColor: 'rgba(75, 192, 192, 0.2)',
        borderColor: 'rgb(75, 192, 192)',
        pointBackgroundColor: 'rgb(75, 192, 192)',
        pointBorderColor: '#fff',
        pointHoverBackgroundColor: '#fff',
        pointHoverBorderColor: 'rgb(75, 192, 192)'
    },
    "yellow":{
        backgroundColor: 'rgba(255, 206, 86, 0.2)',
        borderColor: 'rgb(255, 206, 86)',
        pointBackgroundColor: 'rgb(255, 206, 86)',
        pointBorderColor: '#fff',
        pointHoverBackgroundColor: '#fff',
        pointHoverBorderColor: 'rgb(255, 206, 86)'
    },
    "orange":{
        backgroundColor: 'rgba(255, 159, 64, 0.2)',
        borderColor: 'rgb(255, 159, 64)',
        pointBackgroundColor: 'rgb(255, 159, 64)',
        pointBorderColor: '#fff',
        pointHoverBackgroundColor: '#fff',
        pointHoverBorderColor: 'rgb(255, 159, 64)'
    }
};

function showChart(datajson) {
    tmp = JSON.parse(datajson);
    console.log(tmp);
    id=tmp.id?tmp.id:"";
    //获取数据和名称
    const ctx = document.getElementById("chart-" + id);
    const data = {
        labels: tmp.labels,
        datasets: []
    };
    datas = tmp.datas
    for (i in datas) {
        thisdata = datas[i];
        this1 = {
            label: thisdata.label,
            fill: true,
            data: thisdata.data,
        }
        data.datasets.push({ ...this1, ...colors[thisdata.color] });
    }
    const config = {
        type: tmp.type,
        data: data,
        borderWidth:tmp.borderWidth?tmp.borderWidth:1,
        options: {
            responsive: false, // 设置图表为响应式，根据屏幕窗口变化而变化
            maintainAspectRatio: true,// 保持图表原有比例
            elements: {
                line: {
                    borderWidth: 3 // 设置线条宽度
                }
            },
        }
    };
    //如果tmp.options被设置，那么就将它与config.options合并
    if (tmp.options) {
        config.options = { ...config.options, ...tmp.options }
    }

    if (tmp.height) {
        ctx.style.height = tmp.height;
    }
    if (tmp.width) {
        ctx.style.width = tmp.width;
    } 
    new Chart(ctx, config);
}