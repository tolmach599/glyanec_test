(function (Drupal) {
  Drupal.behaviors.datetimeDisplay = {
    attach: function (context, settings) {
      // Check if idem with 'datetime' id already exists in DOM.
      if (!document.getElementById('datetime')) {
        const datetimeContainer = document.createElement('div');
        datetimeContainer.id = 'datetime';
        document.body.prepend(datetimeContainer);

        function updateDateTime() {
          const now = new Date();
          const date = now.toLocaleDateString('uk-UA', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
          });
          const time = now.toLocaleTimeString('uk-UA', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
          });
          datetimeContainer.textContent = `Current Date and Time added by JS: ${date} ${time}`;
        }

        updateDateTime();
      }
    }
  };
})(Drupal);
