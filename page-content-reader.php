<?php
/**
 * Plugin Name: Page Content Reader
 * Plugin URI: https://example.com/
 * Description: A plugin that adds a "Listen" button to the page, allowing users to listen to the page content.
 * Version: 1.3
 * Author: Your Name
 * Author URI: https://example.com/
 * License: GPL2
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Add settings menu
add_action('admin_menu', 'pcr_add_admin_menu');
function pcr_add_admin_menu() {
    add_options_page(
        'Page Content Reader Settings', 
        'Page Content Reader', 
        'manage_options', 
        'pcr_settings', 
        'pcr_options_page'
    );
}

// Register settings
add_action('admin_init', 'pcr_settings_init');
function pcr_settings_init() {
    register_setting('pcrSettingsGroup', 'pcr_settings');

    add_settings_section(
        'pcr_section_main',
        __('Main Settings', 'pcr'),
        null,
        'pcr_settings'
    );

    add_settings_field(
        'pcr_listen_button_text',
        __('Listen Button Text', 'pcr'),
        'pcr_listen_button_text_render',
        'pcr_settings',
        'pcr_section_main'
    );

    add_settings_field(
        'pcr_listen_button_size',
        __('Listen Button Text Size', 'pcr'),
        'pcr_listen_button_size_render',
        'pcr_settings',
        'pcr_section_main'
    );

    add_settings_field(
        'pcr_listen_button_position',
        __('Listen Button Position', 'pcr'),
        'pcr_listen_button_position_render',
        'pcr_settings',
        'pcr_section_main'
    );

    add_settings_field(
        'pcr_player_bar_bg_color',
        __('Player Bar Background Color', 'pcr'),
        'pcr_player_bar_bg_color_render',
        'pcr_settings',
        'pcr_section_main'
    );

    add_settings_field(
        'pcr_player_bar_text_color',
        __('Player Bar Text Color', 'pcr'),
        'pcr_player_bar_text_color_render',
        'pcr_settings',
        'pcr_section_main'
    );

    add_settings_field(
        'pcr_player_button_bg_color',
        __('Player Button Background Color', 'pcr'),
        'pcr_player_button_bg_color_render',
        'pcr_settings',
        'pcr_section_main'
    );

    add_settings_field(
        'pcr_player_button_text_color',
        __('Player Button Text Color', 'pcr'),
        'pcr_player_button_text_color_render',
        'pcr_settings',
        'pcr_section_main'
    );

    add_settings_field(
        'pcr_pages',
        __('Pages to Display Listen Button', 'pcr'),
        'pcr_pages_render',
        'pcr_settings',
        'pcr_section_main'
    );
}

function pcr_listen_button_text_render() {
    $options = get_option('pcr_settings');
    ?>
    <input type='text' name='pcr_settings[pcr_listen_button_text]' value='<?php echo isset($options['pcr_listen_button_text']) ? $options['pcr_listen_button_text'] : ''; ?>' placeholder="Listen">
    <?php
}

function pcr_listen_button_size_render() {
    $options = get_option('pcr_settings');
    ?>
    <input type='number' name='pcr_settings[pcr_listen_button_size]' value='<?php echo isset($options['pcr_listen_button_size']) ? $options['pcr_listen_button_size'] : ''; ?>' placeholder="16">px
    <?php
}

function pcr_listen_button_position_render() {
    $options = get_option('pcr_settings');
    ?>
    <select name='pcr_settings[pcr_listen_button_position]'>
        <option value='bottom-right' <?php selected(isset($options['pcr_listen_button_position']) ? $options['pcr_listen_button_position'] : '', 'bottom-right'); ?>>Bottom Right</option>
        <option value='bottom-left' <?php selected(isset($options['pcr_listen_button_position']) ? $options['pcr_listen_button_position'] : '', 'bottom-left'); ?>>Bottom Left</option>
        <option value='top-right' <?php selected(isset($options['pcr_listen_button_position']) ? $options['pcr_listen_button_position'] : '', 'top-right'); ?>>Top Right</option>
        <option value='top-left' <?php selected(isset($options['pcr_listen_button_position']) ? $options['pcr_listen_button_position'] : '', 'top-left'); ?>>Top Left</option>
    </select>
    <?php
}

function pcr_player_bar_bg_color_render() {
    $options = get_option('pcr_settings');
    ?>
    <input type='text' name='pcr_settings[pcr_player_bar_bg_color]' value='<?php echo isset($options['pcr_player_bar_bg_color']) ? $options['pcr_player_bar_bg_color'] : '#333'; ?>' placeholder="#333">
    <?php
}

function pcr_player_bar_text_color_render() {
    $options = get_option('pcr_settings');
    ?>
    <input type='text' name='pcr_settings[pcr_player_bar_text_color]' value='<?php echo isset($options['pcr_player_bar_text_color']) ? $options['pcr_player_bar_text_color'] : '#fff'; ?>' placeholder="#fff">
    <?php
}

function pcr_player_button_bg_color_render() {
    $options = get_option('pcr_settings');
    ?>
    <input type='text' name='pcr_settings[pcr_player_button_bg_color]' value='<?php echo isset($options['pcr_player_button_bg_color']) ? $options['pcr_player_button_bg_color'] : '#0073AA'; ?>' placeholder="#0073AA">
    <?php
}

function pcr_player_button_text_color_render() {
    $options = get_option('pcr_settings');
    ?>
    <input type='text' name='pcr_settings[pcr_player_button_text_color]' value='<?php echo isset($options['pcr_player_button_text_color']) ? $options['pcr_player_button_text_color'] : '#fff'; ?>' placeholder="#fff">
    <?php
}

function pcr_pages_render() {
    $options = get_option('pcr_settings');
    $pages = get_pages();
    ?>
    <label><input type="checkbox" id="pcr_mark_all"> Mark All</label><br>
    <label><input type="checkbox" name="pcr_settings[pcr_pages][]" value="all_posts" <?php echo in_array('all_posts', isset($options['pcr_pages']) ? $options['pcr_pages'] : array()) ? 'checked' : ''; ?>> All Blog Posts</label><br>
    <select multiple name='pcr_settings[pcr_pages][]' id="pcr_pages_select">
        <?php
        foreach ($pages as $page) {
            $selected = in_array($page->ID, isset($options['pcr_pages']) ? $options['pcr_pages'] : array()) ? 'selected' : '';
            echo "<option value='$page->ID' $selected>$page->post_title</option>";
        }
        ?>
    </select>
    <p>Hold down the Ctrl (windows) / Command (Mac) button to select multiple options.</p>
    <script>
        document.getElementById('pcr_mark_all').addEventListener('change', function() {
            var options = document.getElementById('pcr_pages_select').options;
            for (var i = 0; i < options.length; i++) {
                options[i].selected = this.checked;
            }
        });
    </script>
    <?php
}

function pcr_options_page() {
    ?>
    <form action='options.php' method='post'>
        <h2>Page Content Reader Settings</h2>
        <?php
        settings_fields('pcrSettingsGroup');
        do_settings_sections('pcr_settings');
        submit_button();
        ?>
    </form>
    <?php
}

// Add Listen button to the frontend based on settings
add_action('wp_head', 'pcr_add_listen_button');
function pcr_add_listen_button() {
    $options = get_option('pcr_settings');

    $page_id = get_the_ID();
    $is_post = is_singular('post');

    if ((!empty($options['pcr_pages']) && in_array('all_posts', $options['pcr_pages']) && $is_post) ||
        (!empty($options['pcr_pages']) && in_array($page_id, $options['pcr_pages']))) {

        // Initialize default values if options are not set
        $text = isset($options['pcr_listen_button_text']) ? $options['pcr_listen_button_text'] : 'Listen';
        $size = isset($options['pcr_listen_button_size']) ? $options['pcr_listen_button_size'] : '16';
        $position = isset($options['pcr_listen_button_position']) ? $options['pcr_listen_button_position'] : 'bottom-right';
        $playerBarBgColor = isset($options['pcr_player_bar_bg_color']) ? $options['pcr_player_bar_bg_color'] : '#333';
        $playerBarTextColor = isset($options['pcr_player_bar_text_color']) ? $options['pcr_player_bar_text_color'] : '#fff';
        $playerButtonBgColor = isset($options['pcr_player_button_bg_color']) ? $options['pcr_player_button_bg_color'] : '#0073AA';
        $playerButtonTextColor = isset($options['pcr_player_button_text_color']) ? $options['pcr_player_button_text_color'] : '#fff';

        ?>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            var listenButton = document.createElement('button');
            listenButton.innerText = '<?php echo $text; ?>';
            listenButton.style.position = 'fixed';
            listenButton.style.bottom = '<?php echo $position === "bottom-right" || $position === "bottom-left" ? "10px" : "auto"; ?>';
            listenButton.style.top = '<?php echo $position === "top-right" || $position === "top-left" ? "10px" : "auto"; ?>';
            listenButton.style.right = '<?php echo $position === "bottom-right" || $position === "top-right" ? "10px" : "auto"; ?>';
            listenButton.style.left = '<?php echo $position === "bottom-left" || $position === "top-left" ? "10px" : "auto"; ?>';
            listenButton.style.zIndex = '1000';
            listenButton.style.padding = '10px';
            listenButton.style.backgroundColor = '#0073AA';
            listenButton.style.color = '#fff';
            listenButton.style.border = 'none';
            listenButton.style.borderRadius = '5px';
            listenButton.style.cursor = 'pointer';
            listenButton.style.fontSize = '<?php echo $size; ?>px';

            document.body.appendChild(listenButton);

            // Create the player bar
            var playerBar = document.createElement('div');
            playerBar.style.position = 'fixed';
            playerBar.style.bottom = '0';
            playerBar.style.left = '0';
            playerBar.style.width = '100%';
            playerBar.style.backgroundColor = '<?php echo $playerBarBgColor; ?>';
            playerBar.style.color = '<?php echo $playerBarTextColor; ?>';
            playerBar.style.display = 'none';
            playerBar.style.flexDirection = 'column';
            playerBar.style.alignItems = 'center';
            playerBar.style.justifyContent = 'center';
            playerBar.style.padding = '10px';
            playerBar.style.zIndex = '1000';

            // Create voice selection dropdown
            var voiceSelectContainer = document.createElement('div');
            voiceSelectContainer.style.display = 'flex';
            voiceSelectContainer.style.alignItems = 'center';
            voiceSelectContainer.style.marginBottom = '10px';

            var voiceSelect = document.createElement('select');
            voiceSelect.style.marginRight = '10px';
            voiceSelect.style.padding = '10px';
            voiceSelect.style.backgroundColor = '<?php echo $playerButtonBgColor; ?>';
            voiceSelect.style.color = '<?php echo $playerButtonTextColor; ?>';
            voiceSelect.style.border = 'none';
            voiceSelect.style.borderRadius = '5px';
            voiceSelect.style.cursor = 'pointer';

            var confirmVoiceButton = document.createElement('button');
            confirmVoiceButton.innerText = 'OK';
            confirmVoiceButton.style.padding = '10px';
            confirmVoiceButton.style.backgroundColor = '<?php echo $playerButtonBgColor; ?>';
            confirmVoiceButton.style.color = '<?php echo $playerButtonTextColor; ?>';
            confirmVoiceButton.style.border = 'none';
            confirmVoiceButton.style.borderRadius = '5px';
            confirmVoiceButton.style.cursor = 'pointer';

            voiceSelectContainer.appendChild(voiceSelect);
            voiceSelectContainer.appendChild(confirmVoiceButton);

            // Create control buttons and progress bar
            var progressBarContainer = document.createElement('div');
            progressBarContainer.style.width = '80%';
            progressBarContainer.style.backgroundColor = '#555';
            progressBarContainer.style.borderRadius = '5px';
            progressBarContainer.style.overflow = 'hidden';
            progressBarContainer.style.position = 'relative';
            progressBarContainer.style.marginBottom = '10px';

            var progressBar = document.createElement('div');
            progressBar.style.width = '0';
            progressBar.style.height = '10px';
            progressBar.style.backgroundColor = '#4CAF50';

            progressBarContainer.appendChild(progressBar);

            var controlsContainer = document.createElement('div');
            controlsContainer.style.display = 'flex';
            controlsContainer.style.justifyContent = 'center';
            controlsContainer.style.alignItems = 'center';

            var prevButton = document.createElement('button');
            prevButton.innerText = 'Prev';
            prevButton.style.marginRight = '10px';
            prevButton.style.padding = '10px';
            prevButton.style.backgroundColor = '<?php echo $playerButtonBgColor; ?>';
            prevButton.style.color = '<?php echo $playerButtonTextColor; ?>';
            prevButton.style.border = 'none';
            prevButton.style.borderRadius = '5px';
            prevButton.style.cursor = 'pointer';

            var playPauseButton = document.createElement('button');
            playPauseButton.innerText = 'Play';
            playPauseButton.style.marginRight = '10px';
            playPauseButton.style.padding = '10px';
            playPauseButton.style.backgroundColor = '<?php echo $playerButtonBgColor; ?>';
            playPauseButton.style.color = '<?php echo $playerButtonTextColor; ?>';
            playPauseButton.style.border = 'none';
            playPauseButton.style.borderRadius = '5px';
            playPauseButton.style.cursor = 'pointer';

            var nextButton = document.createElement('button');
            nextButton.innerText = 'Next';
            nextButton.style.marginRight = '10px';
            nextButton.style.padding = '10px';
            nextButton.style.backgroundColor = '<?php echo $playerButtonBgColor; ?>';
            nextButton.style.color = '<?php echo $playerButtonTextColor; ?>';
            nextButton.style.border = 'none';
            nextButton.style.borderRadius = '5px';
            nextButton.style.cursor = 'pointer';

            var closeButton = document.createElement('button');
            closeButton.innerText = 'Close';
            closeButton.style.padding = '10px';
            closeButton.style.backgroundColor = '#FF0000';
            closeButton.style.color = '#fff';
            closeButton.style.border = 'none';
            closeButton.style.borderRadius = '5px';
            closeButton.style.cursor = 'pointer';

            controlsContainer.appendChild(prevButton);
            controlsContainer.appendChild(playPauseButton);
            controlsContainer.appendChild(nextButton);
            controlsContainer.appendChild(closeButton);

            playerBar.appendChild(voiceSelectContainer);
            playerBar.appendChild(progressBarContainer);
            playerBar.appendChild(controlsContainer);

            document.body.appendChild(playerBar);

            var synth = window.speechSynthesis;
            var utterance;
            var isPlaying = false;
            var isPaused = false;
            var currentIndex = 0;
            var sentences = [];
            var totalSentences = 0;
            var currentSentence;
            var currentCharIndex = 0;

            // Populate voice selection dropdown
            function populateVoiceList() {
                var voices = synth.getVoices();
                voices.forEach(function(voice, index) {
                    var option = document.createElement('option');
                    option.textContent = voice.name + ' (' + voice.lang + ')';
                    option.value = index;
                    voiceSelect.appendChild(option);
                });
            }

            populateVoiceList();
            if (speechSynthesis.onvoiceschanged !== undefined) {
                speechSynthesis.onvoiceschanged = populateVoiceList;
            }

            // Function to read the page content
            function readPageContent() {
                var contentElement = document.querySelector('main'); // Adjust this selector based on your theme
                if (!contentElement) {
                    contentElement = document.body;
                }
                if (contentElement) {
                    var content = contentElement.innerText;
                    sentences = content.split('.').map(s => s.trim()).filter(s => s.length > 0);
                    // Ensure the last sentence is read completely
                    if (sentences[sentences.length - 1].slice(-1) !== '.') {
                        sentences[sentences.length - 1] += '.';
                    }
                    totalSentences = sentences.length;
                    currentIndex = 0;
                    playCurrentSentence();
                }
            }

            // Function to update the progress bar
            function updateProgress() {
                var progress = (currentIndex / totalSentences) * 100;
                progressBar.style.width = progress + '%';
            }

            // Function to play the current sentence
            function playCurrentSentence() {
                if (currentIndex < totalSentences) {
                    currentSentence = sentences[currentIndex];
                    utterance = new SpeechSynthesisUtterance(currentSentence.slice(currentCharIndex));
                    var selectedVoice = synth.getVoices()[voiceSelect.value];
                    utterance.voice = selectedVoice;
                    utterance.lang = 'en-US'; // Change the language if needed

                    utterance.onend = function() {
                        currentCharIndex = 0;
                        currentIndex++;
                        updateProgress();
                        if (isPlaying && currentIndex < totalSentences) {
                            playCurrentSentence();
                        }
                    };

                    utterance.onerror = function() {
                        currentCharIndex = 0;
                        currentIndex++;
                        updateProgress();
                        if (isPlaying && currentIndex < totalSentences) {
                            playCurrentSentence();
                        }
                    };

                    synth.speak(utterance);
                    isPlaying = true;
                    isPaused = false;
                    playPauseButton.innerText = 'Pause';
                    updateProgress();
                } else {
                    resetPlayer();
                }
            }

            // Function to reset the player
            function resetPlayer() {
                isPlaying = false;
                isPaused = false;
                currentCharIndex = 0;
                playPauseButton.innerText = 'Play';
                progressBar.style.width = '0';
                synth.cancel();
            }

            // Event listener for the Play/Pause button
            playPauseButton.addEventListener('click', function() {
                if (isPlaying && !isPaused) {
                    synth.pause();
                    isPaused = true;
                    playPauseButton.innerText = 'Resume';
                } else if (isPlaying && isPaused) {
                    synth.resume();
                    isPaused = false;
                    playPauseButton.innerText = 'Pause';
                } else {
                    readPageContent();
                }
            });

            // Event listener for the Confirm Voice button
            confirmVoiceButton.addEventListener('click', function() {
                if (synth.speaking || isPaused) {
                    synth.cancel();
                    synth.onvoiceschanged = function() {
                        playCurrentSentence();
                    };
                } else {
                    playCurrentSentence();
                }
            });

            // Event listener for the Prev button
            prevButton.addEventListener('click', function() {
                if (currentIndex > 0) {
                    currentIndex -= 2; // Go back two steps so that the currentIndex decreases and the previous sentence is played.
                    if (currentIndex < 0) {
                        currentIndex = 0;
                    }
                    currentCharIndex = 0;
                    synth.cancel();
                    playCurrentSentence();
                }
            });

            // Event listener for the Next button
            nextButton.addEventListener('click', function() {
                if (currentIndex < totalSentences - 1) {
                    currentIndex++;
                    currentCharIndex = 0;
                    synth.cancel();
                    playCurrentSentence();
                }
            });

            // Event listener for the Close button
            closeButton.addEventListener('click', function() {
                isPlaying = false;
                synth.cancel();
                playerBar.style.display = 'none';
                listenButton.style.display = 'block';
                resetPlayer();
            });

            // Event listener for the Listen button
            listenButton.addEventListener('click', function() {
                listenButton.style.display = 'none';
                playerBar.style.display = 'flex';
                readPageContent();
            });

            // Handle page unload
            window.addEventListener('beforeunload', function() {
                if (synth.speaking) {
                    synth.cancel();
                }
            });
        });
        </script>
        <?php
    }
}
