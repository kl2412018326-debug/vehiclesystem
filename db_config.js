// db_config.js - LocalStorage Database Controller
(function () {
    'use strict';

    var DB_NAME = "vehicle_service_db";

    // Safe storage detection
    function getStorage() {
        try {
            return window.localStorage;
        } catch (e) {
            console.warn("LocalStorage access denied or restricted. Falling back to SessionStorage.");
            return window.sessionStorage;
        }
    }

    // Database Initialization
    function initDatabase() {
        var storage = getStorage();
        if (!storage) return;

        try {
            if (!storage.getItem(DB_NAME + '_initialized')) {
                if (!storage.getItem('users')) storage.setItem('users', JSON.stringify([]));
                if (!storage.getItem('vehicles')) storage.setItem('vehicles', JSON.stringify([]));
                if (!storage.getItem('appointments')) storage.setItem('appointments', JSON.stringify([]));
                if (!storage.getItem('service_records')) storage.setItem('service_records', JSON.stringify([]));
                
                storage.setItem(DB_NAME + '_initialized', 'true');
                console.log("Database (" + DB_NAME + ") initialized successfully.");
            }
        } catch (error) {
            console.error("Database initialization failed:", error);
        }
    }

    // Run automatically when script loads
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initDatabase);
    } else {
        initDatabase();
    }
})();
