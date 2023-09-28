<?php
    $folders = logger_list_folders();
    $files = array(4, 5, 6);
?>
<h1>LOGGER</h1><br>
<style>
    select{
        min-width: 150px;

    }
    #loggerTextarea{
        cursor: pointer;
        border:2px solid dimgrey;
        padding:10px 15px;
        color:white;
        background: rgba(0,0,0,0.7);
        transition: 1.9s;
    }
    #loggerTextarea:hover{
        box-shadow: 0 0 20px rgba(0,0,0,0.6);
    }
   .loggerdark {
        border:2px solid dimgrey;
        background: white !important;
        color:black !important;

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
    #loggerTextarea::-webkit-scrollbar-corner{
        opacity: 0.2;
    }
    #loggerTextarea::-webkit-scrollbar-corner:hover{
        opacity: 1;
    }
</style>
<hr>
<div>
<table style="width: 100%;">
    <tr style="text-align: center;">
        <td style="width: 25%">PATH OF LOGS</td>
        <td style="width: 75%">CONTENT<p style="width:fit-content;display:inline;" id="fileName"></p></td>
    </tr>
    <tr>
        <td style="display: block";>
            <table style="margin: 20px auto;">
                <tr>
                    <td>FOLDER</td>
                    <td>
                        <select id="loggerSelectFolders" onchange="change_files_select(this.value)">
                            <option value=""> - </option>
                            <?php foreach ($folders as $f): ?>
                                <option value="<?php echo $f; ?>"><?php echo $f; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>FILE</td>
                    <td>
                        <select id="loggerSelectFiles" onchange="view_file_selected(this.value)">
                            <option value=""> -</option>
                        </select>
                    </td>
                </tr>
            </table>

        </td>
        <td style="text-align: center">
            <textarea title="Click and toggle dark mode" onclick="loggerDark()" class="loggerdark" id="loggerTextarea"  readonly style="width: 90%; margin: 0 auto; height: 75vh; overflow: scroll; border-radius:10px;">
            </textarea>
        </td>
    </tr>
</table>
</div>
