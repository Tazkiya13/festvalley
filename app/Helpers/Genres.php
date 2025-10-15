<?php

namespace App\Helpers;

class Genres
{
    /**
     * Get all available music genres
     */
    public static function getGenres()
    {
        return [
            'Rock' => 'Rock',
            'Pop' => 'Pop',
            'Jazz' => 'Jazz',
            'Blues' => 'Blues',
            'Classical' => 'Classical',
            'Country' => 'Country',
            'Electronic' => 'Electronic',
            'Folk' => 'Folk',
            'Hip Hop' => 'Hip Hop',
            'R&B' => 'R&B',
            'Reggae' => 'Reggae',
            'Punk' => 'Punk',
            'Metal' => 'Metal',
            'Alternative' => 'Alternative',
            'Indie' => 'Indie',
            'Funk' => 'Funk',
            'Soul' => 'Soul',
            'Gospel' => 'Gospel',
            'World Music' => 'World Music',
            'Latin' => 'Latin',
            'Acoustic' => 'Acoustic',
            'Singer-Songwriter' => 'Singer-Songwriter',
            'Experimental' => 'Experimental',
            'Ambient' => 'Ambient',
            'House' => 'House',
            'Techno' => 'Techno',
            'Trance' => 'Trance',
            'Dubstep' => 'Dubstep',
            'Drum & Bass' => 'Drum & Bass',
            'Ska' => 'Ska',
            'Grunge' => 'Grunge',
            'Progressive' => 'Progressive',
            'Psychedelic' => 'Psychedelic',
            'New Age' => 'New Age',
            'Lounge' => 'Lounge',
            'Chillout' => 'Chillout',
            'Other' => 'Other'
        ];
    }

    /**
     * Get genres organized by category
     */
    public static function getGenresByCategory()
    {
        return [
            'Rock & Alternative' => [
                'Rock',
                'Alternative',
                'Indie',
                'Punk',
                'Metal',
                'Grunge',
                'Progressive',
                'Psychedelic'
            ],
            'Pop & Mainstream' => [
                'Pop',
                'Singer-Songwriter',
                'Acoustic',
                'Country'
            ],
            'Electronic & Dance' => [
                'Electronic',
                'House',
                'Techno',
                'Trance',
                'Dubstep',
                'Drum & Bass',
                'Ambient',
                'Chillout'
            ],
            'Soul & Rhythm' => [
                'Jazz',
                'Blues',
                'R&B',
                'Soul',
                'Funk',
                'Gospel',
                'Hip Hop'
            ],
            'World & Traditional' => [
                'Classical',
                'Folk',
                'World Music',
                'Latin',
                'Reggae',
                'Ska',
                'New Age'
            ],
            'Other' => [
                'Experimental',
                'Lounge',
                'Other'
            ]
        ];
    }

    /**
     * Get genres for a specific category
     */
    public static function getGenresForCategory($category)
    {
        $genresByCategory = self::getGenresByCategory();
        return isset($genresByCategory[$category]) ? $genresByCategory[$category] : [];
    }

    /**
     * Get all genre categories
     */
    public static function getGenreCategories()
    {
        return array_keys(self::getGenresByCategory());
    }

    /**
     * Check if a genre exists
     */
    public static function isValidGenre($genre)
    {
        return array_key_exists($genre, self::getGenres());
    }

    /**
     * Get genre categories and their genres as JSON for JavaScript
     */
    public static function getGenresByCategoryAsJson()
    {
        return json_encode(self::getGenresByCategory());
    }

    /**
     * Get all genres as a simple array (for dropdowns)
     */
    public static function getGenresList()
    {
        return array_values(self::getGenres());
    }

    /**
     * Get genre description (optional - can be expanded later)
     */
    public static function getGenreDescription($genre)
    {
        $descriptions = [
            'Rock' => 'A broad genre of popular music that originated as "rock and roll"',
            'Pop' => 'Popular music accessible to the general public',
            'Jazz' => 'A music genre that originated in African-American communities',
            'Blues' => 'A music genre and musical form which originated in the Deep South',
            'Classical' => 'Art music produced or rooted in Western musical traditions',
            'Electronic' => 'Music that employs electronic musical instruments',
            'Folk' => 'Traditional music that has been passed down through generations',
            'Hip Hop' => 'A cultural movement that includes rap music and other elements',
            'R&B' => 'Rhythm and blues, a genre combining jazz, gospel, and blues',
            // Add more descriptions as needed
        ];

        return isset($descriptions[$genre]) ? $descriptions[$genre] : 'A music genre';
    }
}
