document.addEventListener('alpine:init', () => {
  Alpine.data('form', () =>
    ezekiel.makeForm({
      _schema: {
        field_1: ['required', 'rule'],
        field_2: ['required', 'rule'],
      },

      init() {
        this.initForm(this);
      },

      async submit() {
        if (this.loading) return;

        this.loading = true;
        this.error = null;

        const response = await api.fetch('/endpoint', {
          method: 'METHOD',
          body: this.payload,
        });

        if (response.failed) {
          this.error = 'error';
          this.loading = false;
          return;
        }

        // this.redirect();
      },
    })
  );
});
