/*document.addEventListener('DOMContentLoaded', function() {
  // Function to update the progress bar and countdown timer
  function updateProgress() {
      const currentWeek = 12; // Example week
      const totalWeeks = 40; // Total duration of pregnancy
      const progressPercentage = (currentWeek / totalWeeks) * 100;

      // Select the progress bar and text elements
      const progressBar = document.querySelector('.progress');
      const progressText = document.querySelector('.progress-text');

      // Check if elements exist before trying to access their properties
      if (progressBar && progressText) {
          progressBar.style.width = progressPercentage + '%';
          progressText.textContent = Math.round(progressPercentage) + '% Complete';
      } else {
          console.error('Progress bar or text element not found.');
      }

      // Countdown Timer Logic
      const dueDate = new Date(); // Set the current date
      dueDate.setDate(dueDate.getDate() + ((totalWeeks - currentWeek) * 7)); // Add remaining days to due date

      const timer = setInterval(() => {
          const now = new Date();
          const distance = dueDate - now;

          // Time calculations
          const days = Math.floor(distance / (1000 * 60 * 60 * 24));
          const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
          const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
          const seconds = Math.floor((distance % (1000 * 60)) / 1000); // Calculate total seconds

          // Update countdown display
          const daysElement = document.getElementById('days');
          const hoursElement = document.getElementById('hours');
          const minutesElement = document.getElementById('minutes');
          const secondsElement = document.getElementById('seconds');

          if (daysElement && hoursElement && minutesElement && secondsElement) {
              daysElement.textContent = days < 10 ? '0' + days : days;
              hoursElement.textContent = hours < 10 ? '0' + hours : hours;
              minutesElement.textContent = minutes < 10 ? '0' + minutes : minutes;
              secondsElement.textContent = seconds < 10 ? '0' + seconds : seconds; // Update seconds
          } else {
              console.error('Countdown timer elements not found.');
          }

          // If the countdown is over, stop the timer
          if (distance < 0) {
              clearInterval(timer);
              document.querySelector('.countdown-timer').textContent = "Due Date Reached!";
          }
      }, 1000);
  }

  // Initialize the progress and countdown
  updateProgress();
});
*/