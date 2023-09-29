<h1><?php echo __('LOGGER', 'logger-plugin'); ?></h1><br>
<hr>
<div>
    <h2><?php echo __('DESCRIPTION', 'logger-plugin'); ?></h2>

    <p><?php echo __('LOGGER needs jQuery to work and is designed for development. All logs are divided into subfolders and archived by date.', 'logger-plugin'); ?></p>
    <p><?php echo __('LOGGER may write many files and for this reason it is important to store the data logs externally and delete, if necessary, only the contents of the ' . "'logs'" . ' folder in the LOGGER plugin.', 'logger-plugin'); ?></p>
    <p><?php echo __('LOGGER can clearly log arguments of type: String, Array, Object, Array of Objects; A LOG has the following structure:', 'logger-plugin'); ?>
        <br>
        <?php echo __('** LOGGER ** | {Type of LOG} | {-->Functions backtrace} - {Description (optionally)} ~ {Argument(Array, String, Object)}.', 'logger-plugin'); ?>
    </p>

    <h2><?php echo __('LOG FUNCTIONS', 'logger-plugin'); ?></h2>

    <p><?php echo __('LOGGER has 5 functions to insert a LOG wherever you want.', 'logger-plugin'); ?></p>
    <p>
        <?php echo __('Function 1 - do_log( $type, $argument, $description(optional) ) -- Save a Log in the structure as described before. The $type parameter can be customized.', 'logger-plugin'); ?>
        <br>
        <?php echo __('Function 2 - logger_info( $argument, $description(optional) ) -- So $type parameter will be ' . "'" . 'INFO' . "'.", 'logger-plugin'); ?>
        <br>
        <?php echo __('Function 3 - logger_success( $argument, $description(optional) ) -- So $type parameter will be ' . "'" . 'SUCCESS' . "'.", 'logger-plugin'); ?>
        <br>
        <?php echo __('Function 4 - logger_error( $argument, $description(optional) ) -- So $type parameter will be ' . "'" . 'ERROR' . "'.", 'logger-plugin'); ?>
        <br>
        <?php echo __('Function 5 - logger_warning( $argument, $description(optional) ) -- So $type parameter will be ' . "'" . 'WARNING' . "'.", 'logger-plugin'); ?>
        <br>
    </p><br><br>
    <hr>
    <h2><?php echo __('A TIP', 'logger-plugin'); ?></h2>
    <p><?php echo __('When using LOGGER functions in your project, check the existence of the function before the calling: ` if( function_exist( `do_log` ) ) { do_log(...); } ` So when the plugin is disabled all functions will not throw errors!', 'logger-plugin'); ?></p>
</div>
