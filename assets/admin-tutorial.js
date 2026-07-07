(function () {
  'use strict';

  var steps = window.khTutorialSteps || [];
  var dismissUrl = window.khTutorialDismissUrl || '';
  var currentStep = 0;

  var overlay = document.getElementById('kh-tutorial-overlay');
  if (!overlay || steps.length === 0) return;

  var backdrop = overlay.querySelector('.kh-tutorial-backdrop');
  var spotlight = overlay.querySelector('.kh-tutorial-spotlight');
  var tooltip = overlay.querySelector('.kh-tutorial-tooltip');
  var titleEl = overlay.querySelector('.kh-tutorial-title');
  var contentEl = overlay.querySelector('.kh-tutorial-content');
  var stepCurrent = overlay.querySelector('.kh-tutorial-step-current');
  var stepTotal = overlay.querySelector('.kh-tutorial-step-total');
  var btnPrev = overlay.querySelector('.kh-tutorial-btn-prev');
  var btnNext = overlay.querySelector('.kh-tutorial-btn-next');
  var btnSkip = overlay.querySelector('.kh-tutorial-btn-skip');

  stepTotal.textContent = steps.length;

  function showStep(index) {
    currentStep = index;
    var step = steps[index];

    // Update content
    titleEl.textContent = step.title;
    contentEl.textContent = step.content;
    stepCurrent.textContent = index + 1;

    // Button states
    btnPrev.disabled = index === 0;
    btnNext.textContent = index === steps.length - 1 ? 'Finish' : 'Next';

    // Find target element
    var target = step.target ? document.querySelector(step.target) : null;

    if (target && target.offsetParent !== null) {
      // Highlight the target
      var rect = target.getBoundingClientRect();
      var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
      var scrollLeft = window.pageXOffset || document.documentElement.scrollLeft;

      var padding = 8;
      spotlight.style.display = 'block';
      spotlight.style.top = (rect.top + scrollTop - padding) + 'px';
      spotlight.style.left = (rect.left + scrollLeft - padding) + 'px';
      spotlight.style.width = (rect.width + padding * 2) + 'px';
      spotlight.style.height = (rect.height + padding * 2) + 'px';

      // Position tooltip relative to target
      positionTooltip(rect, step.position, scrollTop, scrollLeft);

      // Scroll target into view if needed
      if (rect.top < 0 || rect.bottom > window.innerHeight) {
        target.scrollIntoView({ behavior: 'smooth', block: 'center' });
        // Reposition after scroll
        setTimeout(function () {
          var newRect = target.getBoundingClientRect();
          var newScrollTop = window.pageYOffset || document.documentElement.scrollTop;
          var newScrollLeft = window.pageXOffset || document.documentElement.scrollLeft;
          spotlight.style.top = (newRect.top + newScrollTop - padding) + 'px';
          spotlight.style.left = (newRect.left + newScrollLeft - padding) + 'px';
          spotlight.style.width = (newRect.width + padding * 2) + 'px';
          spotlight.style.height = (newRect.height + padding * 2) + 'px';
          positionTooltip(newRect, step.position, newScrollTop, newScrollLeft);
        }, 400);
      }
    } else {
      // Center the tooltip (no target)
      spotlight.style.display = 'none';
      tooltip.style.position = 'fixed';
      tooltip.style.top = '50%';
      tooltip.style.left = '50%';
      tooltip.style.transform = 'translate(-50%, -50%)';
      tooltip.className = 'kh-tutorial-tooltip kh-tutorial-tooltip-center';
    }
  }

  function positionTooltip(rect, position, scrollTop, scrollLeft) {
    var tooltipWidth = 380;
    var gap = 16;

    tooltip.style.position = 'absolute';
    tooltip.style.transform = '';
    tooltip.className = 'kh-tutorial-tooltip kh-tutorial-tooltip-' + position;

    switch (position) {
      case 'bottom':
        tooltip.style.top = (rect.bottom + scrollTop + gap) + 'px';
        tooltip.style.left = (rect.left + scrollLeft + rect.width / 2 - tooltipWidth / 2) + 'px';
        break;
      case 'top':
        tooltip.style.top = (rect.top + scrollTop - gap - tooltip.offsetHeight) + 'px';
        tooltip.style.left = (rect.left + scrollLeft + rect.width / 2 - tooltipWidth / 2) + 'px';
        break;
      case 'left':
        tooltip.style.top = (rect.top + scrollTop + rect.height / 2 - tooltip.offsetHeight / 2) + 'px';
        tooltip.style.left = (rect.left + scrollLeft - tooltipWidth - gap) + 'px';
        break;
      case 'right':
        tooltip.style.top = (rect.top + scrollTop + rect.height / 2 - tooltip.offsetHeight / 2) + 'px';
        tooltip.style.left = (rect.right + scrollLeft + gap) + 'px';
        break;
      default:
        tooltip.style.top = (rect.bottom + scrollTop + gap) + 'px';
        tooltip.style.left = (rect.left + scrollLeft + rect.width / 2 - tooltipWidth / 2) + 'px';
    }

    // Keep tooltip within viewport horizontally
    var tooltipRect = tooltip.getBoundingClientRect();
    if (tooltipRect.right > window.innerWidth - 20) {
      tooltip.style.left = (window.innerWidth - tooltipWidth - 40) + 'px';
    }
    if (tooltipRect.left < 20) {
      tooltip.style.left = '20px';
    }
  }

  function dismiss() {
    overlay.classList.add('kh-tutorial-hiding');
    setTimeout(function () {
      overlay.style.display = 'none';
    }, 300);

    // Tell WordPress to clear the flag
    if (dismissUrl) {
      fetch(dismissUrl, { method: 'GET', credentials: 'same-origin' });
    }
  }

  // Event listeners
  btnNext.addEventListener('click', function () {
    if (currentStep < steps.length - 1) {
      showStep(currentStep + 1);
    } else {
      dismiss();
    }
  });

  btnPrev.addEventListener('click', function () {
    if (currentStep > 0) {
      showStep(currentStep - 1);
    }
  });

  btnSkip.addEventListener('click', dismiss);

  // Close on backdrop click
  backdrop.addEventListener('click', dismiss);

  // Close on Escape
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      dismiss();
    }
  });

  // Start the tutorial
  showStep(0);
})();
