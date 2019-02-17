export const planetChartData = {
  type: 'bar',
  data: {
    labels: ['2000', '2001', '2002', '2003', '2004', '2005', '2006', '2007', '2008', '2009', '2010', '2011', '2012', '2013', '2014', '2015', '2016'],
    datasets: [
      { // one line graph
        label: 'Number of sign-ups per year',
        data: [0, 0, 1, 2, 67, 62, 27, 14],
        backgroundColor: [
          'rgba(54,73,93,.5)', // Blue
          'rgba(54,73,93,.5)',
          'rgba(54,73,93,.5)',
          'rgba(54,73,93,.5)',
          'rgba(54,73,93,.5)',
          'rgba(54,73,93,.5)',
          'rgba(54,73,93,.5)',
          'rgba(54,73,93,.5)'
        ],
        borderColor: [
          '#36495d',
          '#36495d',
          '#36495d',
          '#36495d',
          '#36495d',
          '#36495d',
          '#36495d',
          '#36495d',
        ],
        borderWidth: 3
      },
      { // another line graph
        label: 'Number of sign-ups',
        data: [62, 53, 63, 71, 49, 47, 59, 59, 60, 47, 64, 60, 55, 67, 60, 55, 59],
        backgroundColor: [
          'rgba(71, 183,132,.5)', // Green
        ],
        borderColor: [
          '#47b784',
        ],
        borderWidth: 3
      }
    ]
  },
  options: {
    responsive: true,
    lineTension: 1,
    scales: {
      yAxes: [{
        ticks: {
          beginAtZero: true,
          padding: 25,
        }
      }]
    }
  }
}

export default planetChartData;
