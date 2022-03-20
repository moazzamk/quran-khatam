jQuery(document).ready(function ($) {
   $('.datepicker').datepicker();

   const registeredRecitors = parseInt($('#registered-recitors').val());
   const finishedRecitors = parseInt($('#finished-recitors').val());

   console.log(registeredRecitors, finishedRecitors, 'hiii');

   // Registered users chart
   const ctx = document.getElementById('registered-users').getContext('2d');
   const myChart = new Chart(ctx, {
      type: 'doughnut',
      data: {
         labels: ['Registered', 'Not'],
         datasets: [{
            label: '# of Votes',
            data: [registeredRecitors, 30 - registeredRecitors],
            backgroundColor: [
               'rgba(54, 162, 235, 0.5)',
               'rgba(255, 99, 132, 0.5)',

            ],
            borderColor: [
               'rgba(54, 162, 235, .6)',
               'rgba(255, 99, 132, .6)',

            ],
            borderWidth: 1
         }]
      },
      options: {
         layout: {
            padding: 10
         }
      }
   });

   // Recited users chart
   const ctx1 = document.getElementById('reciting-users').getContext('2d');
   const myChart1 = new Chart(ctx1, {
      type: 'doughnut',
      data: {
         labels: ['Done', 'Still reciting'],
         datasets: [{
            label: '# of Votes',
            // for some reason this chart doesn't work if all values are zero. thus, math.max
            data: [finishedRecitors, Math.max(registeredRecitors - finishedRecitors, 1)],
            backgroundColor: [
               'rgba(0, 200, 50, 0.5)',
               'rgba(255, 255, 255, 0.5)',

            ],
            borderColor: [
               'rgba(0, 200, 50, 0.6)',
               'rgba(0, 200, 50, 0.6)',

            ],
            borderWidth: 1
         }]
      },
      options: {
         layout: {
            padding: 10
         }
      }
   });

   Chart.Doughnut.default.labelFontFamily = 'Arial';
   Chart.Doughnut.default.labelFontStyle = 'normal';
   Chart.Doughnut.default.labelFontColor = '#000';
   ctx1.fillText("22%", 300/2 - 20, 300/2, 200);


}(jQuery));