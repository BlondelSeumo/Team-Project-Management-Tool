"use strict";

Dropzone.autoDiscover = false;
var dropzone = new Dropzone("#mydropzone", {
  url: base_url+"projects/upload-files/"+project_id
});
dropzone.on("complete", function(file) {
  $('#file_list').bootstrapTable('refresh');
});


var ctx = document.getElementById("project_statistics").getContext('2d');
var myChart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: JSON.parse(task_status),
    datasets: [{
      label: 'Task Status',
      data: JSON.parse(task_status_values),
      borderWidth: 2,
      backgroundColor: [
        '#fc544b',
        '#ffa426',
        '#3abaf4',
        '#63ed7a',
      ]
    }]
  },
  options: {
    legend: {
      display: false
    },
    scales: {
      yAxes: [{
        gridLines: {
          drawBorder: false,
          color: '#f2f2f2',
        },
        ticks: {
          beginAtZero: true,
          stepSize: 1
        }
      }],
      xAxes: [{
        gridLines: {
          display: false
        }
      }]
    },
  }
});