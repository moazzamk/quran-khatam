jQuery(document).ready(function ($) {
   $('.datepicker').datepicker();

   // Registered users chart
   const ctx = document.getElementById('registered-users').getContext('2d');
   const myChart = new Chart(ctx, {
      type: 'doughnut',
      data: {
         labels: ['Registered', 'Not'],
         datasets: [{
            label: '# of Votes',
            data: [20, 10],
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
            data: [12, 19],
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
         elements: {
            center: {
               text: '82%',
            }
         },

         layout: {
            padding: 10
         }
      }
   });
   console.log(Object.keys(Chart));
   Chart.Doughnut.default.labelFontFamily = 'Arial';
   Chart.Doughnut.default.labelFontStyle = 'normal';
   Chart.Doughnut.default.labelFontColor = '#000';
   ctx1.fillText("22%", 300/2 - 20, 300/2, 200);


}(jQuery));