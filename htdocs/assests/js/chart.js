// Wait for DOM to load (optional but recommended)
document.addEventListener("DOMContentLoaded", function() {
  const userCtx = document.getElementById('userGrowthChart').getContext('2d');
  new Chart(userCtx, {
    type: 'bar',
    data: {
      labels: userLabels,
      datasets: [{
        label: 'Users Registered',
        data: userCounts,
        backgroundColor: 'rgba(75, 192, 192, 0.6)',
        borderColor: 'rgba(75, 192, 192, 1)',
        borderWidth: 1
      }]
    },
    options: { responsive: true }
  });

  const postCtx = document.getElementById('postActivityChart').getContext('2d');
  new Chart(postCtx, {
    type: 'bar',
    data: {
      labels: postLabels,
      datasets: [{
        label: 'Posts',
        data: postCounts,
        backgroundColor: 'rgba(153, 102, 255, 0.6)',
        borderColor: 'rgba(153, 102, 255, 1)',
        borderWidth: 1
      }]
    },
    options: { responsive: true }
  });
});
