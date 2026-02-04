<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Firebase Credentials
    |--------------------------------------------------------------------------
    |
    | Path to your Firebase service account JSON file.
    |
    */
    'credentials' => env('FIREBASE_CREDENTIALS', storage_path('app/firebase/firebase-credentials.json')),

    /*
    |--------------------------------------------------------------------------
    | Firebase Database URL
    |--------------------------------------------------------------------------
    |
    | Your Firebase Realtime Database URL.
    |
    */
    'database_url' => env('FIREBASE_DATABASE_URL'),

    /*
    |--------------------------------------------------------------------------
    | Firebase Project ID
    |--------------------------------------------------------------------------
    |
    | Your Firebase project ID (for Firestore).
    |
    */
    'project_id' => env('FIREBASE_PROJECT_ID'),
];
