<?php

/**
 * Function to censor text by replacing certain words with 'splode'.
 *
 * This version enhances the filtering logic to:
 * - Treat words like "example1" as a single unit, but ignore internal punctuation (e.g., "exampl.e1").
 * - Not censor if the word is split by a space (e.g., "example 1" if "example1" is the censored word).
 * - Not censor if the word is part of a larger alphanumeric word (e.g., "aexample1a" will not censor "example1").
 * - Correctly handle trailing punctuation (e.g., "example1." will be censored, preserving the dot).
 *
 * @param string $text The text to be censored.
 * @return string The censored text. Returns the original text if it's empty,
 * or if no censored words are defined.
 */
function censorText(string $text): string {
    // Return early if text is empty, as there's nothing to censor.
    if (empty($text)) {
        return $text;
    }

    // Retrieve censored words from environment variable.
    // CENSORED_WORDS should be a comma-separated string (e.g., "badword,anotherbad").
    // Added a fallback for development/testing if environment variable is not set.
    $censoredWordsEnv = getenv('CENSORED_WORDS');

    // Proceed only if censored words are defined.
    if ($censoredWordsEnv) {
        // Split the environment variable string into an array of words.
        $censoredWords = explode(',', $censoredWordsEnv);

        // Iterate over each word to create and apply a specific regex pattern.
        foreach ($censoredWords as $word) {
            $word = trim($word); // Clean up whitespace around the word.

            // Only process non-empty words.
            if ($word !== '') {
                // Split the word into individual letters for pattern construction.
                // Using PREG_SPLIT_NO_EMPTY ensures no empty array elements from the split.
                $letters = preg_split('//u', $word, -1, PREG_SPLIT_NO_EMPTY);
                $corePattern = '';

                // Build the core of the regex pattern for the word.
                // This allows zero or more characters that are NOT alphanumeric AND NOT whitespace
                // between the letters of the censored word.
                foreach ($letters as $i => $ch) {
                    $corePattern .= preg_quote($ch, '/'); // Quote special regex characters in the letter.
                    if ($i < count($letters) - 1) {
                        // Use a non-capturing group for the interstitial characters.
                        // This allows any sequence of non-alphanumeric, non-whitespace characters.
                        $corePattern .= '(?:[^a-zA-Z0-9\s]*)';
                    }
                }

                // Construct the full regex pattern with explicit lookarounds for precise word boundary matching.
                // Explanation of the full pattern:
                // `(?<![a-zA-Z0-9])`: Negative lookbehind. Asserts that the match is NOT preceded by an alphanumeric character.
                //                     This ensures the match occurs at the start of a string, or after a non-alphanumeric character.
                //                     This is crucial for preventing "aexample1a" from being filtered.
                // `(` . $corePattern . `)`: The core word pattern, matching the letters of the censored word
                //                           and any internal non-alphanumeric, non-whitespace characters (like a dot).
                //                           This is our primary capture group for the word itself.
                // `([.,!?:;]?)`: Capturing group. Optionally matches and captures any single trailing punctuation mark
                //                from the specified set. This allows us to preserve it during replacement.
                // `(?![a-zA-Z0-9])`: Negative lookahead. Asserts that the match (including any captured trailing punctuation)
                //                    is NOT followed by an alphanumeric character.
                //                    This ensures the match occurs at the end of a string, or before a non-alphanumeric character.
                //                    This also contributes to preventing "aexample1a" from being filtered.
                // `/iu`: Flags: 'i' for case-insensitive matching, 'u' for UTF-8 matching.
                $pattern = '/(?<![a-zA-Z0-9])(' . $corePattern . ')([.,!?:;]?)(?![a-zA-Z0-9])/iu';

                // Replace the matched word with 'splode', re-appending any captured trailing punctuation.
                // $matches[0] is the entire matched string.
                // $matches[1] is the content of the first capturing group (our $corePattern match).
                // $matches[2] is the content of the second capturing group (the optional trailing punctuation).
                $text = preg_replace_callback($pattern, function($matches) {
                    return 'splode' . ($matches[2] ?? '');
                }, $text);
            }
        }
    }
    // Return the (potentially) censored text.
    return $text;
}
