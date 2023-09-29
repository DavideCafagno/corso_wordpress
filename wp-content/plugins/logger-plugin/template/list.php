<style>
    select {
        min-width: 150px;

    }

    #loggerTextarea {
        cursor: pointer;
        border: 2px solid dimgrey;
        padding: 10px 15px;
        color: white;
        background: rgba(0, 0, 0, 0.8);
        transition: 1.9s;
    }

    #darkmodeicon {
        width: fit-content;
        height: fit-content;
        padding: 5px;
        border-radius: 10%;
        border: none;
        transition: 0.5s !important;
        display: inline !important;
        background: none;
    }

    #darkmodeicon:hover {
        cursor: pointer !important;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.6) !important;
    }

    #loggerdarkicon {
        transition: 0.8s;
    }

    .invert {
        filter: invert(100%);
    }

    .darker {
        background: rgba(0, 0, 0, 0.8) !important;
    }

    #loggerTextarea:hover {
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.6);
    }

    .loggerdark {
        border: 2px solid dimgrey;
        background: white !important;
        color: black !important;

    }

    #loggerTextarea::-webkit-scrollbar {
        width: 10px;
    }

    /* Track */
    #loggerTextarea::-webkit-scrollbar-track {
        background: #f1f1f100;
    }

    /* Handle */
    #loggerTextarea::-webkit-scrollbar-thumb {
        background: #888;
    }

    /* Handle on hover */
    #loggerTextarea::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    #loggerTextarea::-webkit-scrollbar-corner {
        opacity: 0.2;
    }

    #loggerTextarea::-webkit-scrollbar-corner:hover {
        opacity: 1;
    }

    @media screen and (min-width: 900px) {
        #loggercontainer{
            display: inline-flex;
            align-items: flex-start;
        }
    }
</style>


<?php
$folders = logger_list_folders();
?>
<h1><?php echo __('LOGGER', 'logger-plugin'); ?></h1><br>
<hr>
<div id="loggercontainer" style="width: 100%;">
    <table style="width: 100%;">
        <tr style="text-align: center;">
            <td ><h3><b><?php echo __('SELECT YOUR LOG FILE', 'logger-plugin'); ?></b></h3></td>


        </tr>
        <tr>
            <td style="display: block" ;>
                <table style="margin: 20px auto;">
                    <tr>
                        <td><?php echo __('FOLDER', 'logger-plugin'); ?></td>
                        <td>
                            <select id="loggerSelectFolders" onchange="change_files_select(this.value)">
                                <option value=""> -</option>
                                <?php foreach ($folders as $f): ?>
                                    <option value="<?php echo $f; ?>"><?php echo $f; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo __('FILE', 'logger-plugin'); ?></td>
                        <td>
                            <select id="loggerSelectFiles" onchange="view_file_selected(this.value)">
                                <option value=""> -</option>
                            </select>
                        </td>
                    </tr>
                </table>
            </td>

        </tr>
    </table>

    <table style="width: 100%; min-width: 70vw;">
        <tr style="text-align: center;">

            <td style="width: 75%;display: inline-flex; justify-content: space-between;align-items: center;">
                <h3><b><?php echo __('CONTENT', 'logger-plugin'); ?><span style="width:fit-content;display:inline;"
                                                                          id="fileName"></span></b></h3>
                <button id="darkmodeicon" title="<?php echo __('Click to switch light/dark mode', 'logger-plugin'); ?>"
                        onclick="loggerDark()"><span id="loggerdarkicon"
                                                     class="wp-menu-image dashicons-before dashicons-star-half"></span>
                </button>
            </td>
        </tr>
        <tr>

            <td style="text-align: center">
            <textarea class="loggerdark" id="loggerTextarea"
                      readonly style="width: 90%; margin: 0 auto; min-height: 75vh; overflow: scroll; border-radius:10px;">
            </textarea>
            </td>
        </tr>
    </table>
</div>