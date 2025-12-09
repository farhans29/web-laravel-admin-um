/**
 * Date Filter Persistence Utility
 * Stores and retrieves date filter values using localStorage
 */

const DateFilterPersistence = {
    /**
     * Get the storage key for the current page
     * @param {string} pageIdentifier - Unique identifier for the page
     * @returns {string}
     */
    getStorageKey: function(pageIdentifier) {
        return `date_filter_${pageIdentifier}`;
    },

    /**
     * Save date filter to localStorage
     * @param {string} pageIdentifier - Unique identifier for the page
     * @param {string} startDate - Start date in YYYY-MM-DD format
     * @param {string} endDate - End date in YYYY-MM-DD format
     */
    save: function(pageIdentifier, startDate, endDate) {
        const data = {
            start_date: startDate,
            end_date: endDate,
            timestamp: new Date().getTime()
        };
        localStorage.setItem(this.getStorageKey(pageIdentifier), JSON.stringify(data));
    },

    /**
     * Load date filter from localStorage
     * @param {string} pageIdentifier - Unique identifier for the page
     * @returns {object|null} Object with start_date and end_date, or null if not found
     */
    load: function(pageIdentifier) {
        const stored = localStorage.getItem(this.getStorageKey(pageIdentifier));
        if (stored) {
            try {
                return JSON.parse(stored);
            } catch (e) {
                console.error('Error parsing stored date filter:', e);
                return null;
            }
        }
        return null;
    },

    /**
     * Clear date filter from localStorage
     * @param {string} pageIdentifier - Unique identifier for the page
     */
    clear: function(pageIdentifier) {
        localStorage.removeItem(this.getStorageKey(pageIdentifier));
    },

    /**
     * Initialize flatpickr with persistence
     * @param {string} pageIdentifier - Unique identifier for the page
     * @param {object} options - Flatpickr options
     * @returns {object} Flatpickr instance
     */
    initFlatpickr: function(pageIdentifier, options = {}) {
        const stored = this.load(pageIdentifier);
        const self = this;

        // Determine initial dates
        let initialStartDate, initialEndDate;

        if (stored && stored.start_date && stored.end_date) {
            // Use stored dates
            initialStartDate = new Date(stored.start_date);
            initialEndDate = new Date(stored.end_date);
        } else if (options.defaultStartDate && options.defaultEndDate) {
            // Use provided defaults
            initialStartDate = options.defaultStartDate;
            initialEndDate = options.defaultEndDate;
        } else {
            // Use today and one month ahead as fallback
            initialStartDate = new Date();
            initialEndDate = new Date();
            initialEndDate.setMonth(initialEndDate.getMonth() + 1);
        }

        // Set hidden input values
        if (document.getElementById('start_date')) {
            document.getElementById('start_date').value = this.formatDate(initialStartDate);
        }
        if (document.getElementById('end_date')) {
            document.getElementById('end_date').value = this.formatDate(initialEndDate);
        }

        // Merge default options with user options
        const flatpickrOptions = {
            mode: "range",
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "j F Y",
            allowInput: true,
            static: true,
            monthSelectorType: 'static',
            defaultDate: [initialStartDate, initialEndDate],
            minDate: options.minDate || "today",
            maxDate: options.maxDate || new Date().fp_incr(365),
            onOpen: function(selectedDates, dateStr, instance) {
                if (options.onOpen) {
                    options.onOpen(selectedDates, dateStr, instance);
                } else {
                    instance.set('minDate', null);
                }
            },
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length > 0) {
                    const startDate = selectedDates[0];
                    const endDate = selectedDates[1] || selectedDates[0];

                    // Check max range if specified
                    if (options.maxRangeDays) {
                        const diffInDays = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24)) + 1;

                        if (diffInDays > options.maxRangeDays) {
                            if (window.Swal) {
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'warning',
                                    title: `Maximum date range is ${options.maxRangeDays - 1} days`,
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                    didOpen: (toast) => {
                                        toast.addEventListener('mouseenter', Swal.stopTimer);
                                        toast.addEventListener('mouseleave', Swal.resumeTimer);
                                    }
                                });
                            }

                            instance.clear();
                            document.getElementById('start_date').value = '';
                            document.getElementById('end_date').value = '';
                            self.clear(pageIdentifier);
                            return;
                        }
                    }

                    // Format and save dates
                    const formattedStart = self.formatDate(startDate);
                    const formattedEnd = self.formatDate(endDate);

                    document.getElementById('start_date').value = formattedStart;
                    document.getElementById('end_date').value = formattedEnd;

                    // Save to localStorage
                    self.save(pageIdentifier, formattedStart, formattedEnd);

                    // Call custom onChange if provided
                    if (options.onChange) {
                        options.onChange(selectedDates, dateStr, instance);
                    }
                }
            },
            onClose: function(selectedDates, dateStr, instance) {
                if (selectedDates.length === 0) {
                    document.getElementById('start_date').value = '';
                    document.getElementById('end_date').value = '';
                    self.clear(pageIdentifier);
                }

                // Call custom onClose if provided
                if (options.onClose) {
                    options.onClose(selectedDates, dateStr, instance);
                }
            }
        };

        return flatpickr("#date_picker", flatpickrOptions);
    },

    /**
     * Format date to YYYY-MM-DD
     * @param {Date} date
     * @returns {string}
     */
    formatDate: function(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }
};
