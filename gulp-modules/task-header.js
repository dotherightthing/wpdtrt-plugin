/**
 * File: gulp-modules/task-header.js
 *
 * Helper functions to visually organise build logs.
 */

import log from 'fancy-log';

/**
 * Group: Helpers
 * _____________________________________
 */

/**
 * Function: taskHeader
 *
 * Displays a block comment for each task that runs.
 *
 * Parameters:
 *   (string) step - Step number
 *   (string) taskCategory - Task category
 *   (string) taskAction - Task action
 *   (string) taskDetail - Task detail
 */
function taskHeader(
  step = '0',
  taskCategory = '',
  taskAction = '',
  taskDetail = ''
) {
  log( ' ' );
  log( '========================================' );
  log( `${step} - ${taskCategory}:` );
  log( `=> ${taskAction}: ${taskDetail}` );
  log( '----------------------------------------' );
  log( ' ' );
}

export default taskHeader;
