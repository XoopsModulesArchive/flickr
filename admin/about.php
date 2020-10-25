<?php

global $xoopsModule;
$moduleHandler = xoops_getHandler('module');
$flickr_info = $moduleHandler->get($xoopsModule->getVar('mid'));

echo "<table border='0'>";
echo '<tr>';
echo "<td><img src='" . FLICKR_IMAGE_URL . "/flickr_logo.png'></td>";
echo "<td><div style='margin-top: 0px; color: #2F5376; margin-bottom: 4px; font-size: 18px; line-height: 18px; font-weight: bold; display: block;'>" . $flickr_info->getInfo('name') . ' version ' . $flickr_info->getInfo('version') . ' (' . $flickr_info->getInfo('version_info') . ')</div>';
echo "<div style = 'line-height: 16px; font-weight: bold; display: block;'>" . _AM_FLICKR_TEXT_BY . ' ' . $flickr_info->getInfo('creator') . '</div>';
echo '<div>' . $flickr_info->getInfo('license') . '</div>';
echo '</td></tr>';
echo '</table><br>';

echo "<table width='100%' border='0' cellspacing='1' class='outer'>";
echo "<tr><th colspan='2'>" . _AM_FLICKR_TEXT_CONTRIB_INFO . '</th></tr>';
echo "<tr><td class='head' width='20%' valign='top'>" . _AM_FLICKR_TEXT_DEVELOPERS . '</td>';
echo "<td class='even' valign='top'>";
$contributors = $flickr_info->getInfo('contributors');
$devs = $contributors['developers'];
echo "<table border='0'>";      //Create nested table for developers
foreach ($devs as $dev) {
    echo "<tr><td><a href='mailto: " . $dev['email'] . "'>" . $dev['uname'] . '</a> (' . $dev['name'] . ')</td>
              </tr>';
}
echo '</table>';
echo '</tr>';       //end of nested developers table

if (isset($contributors['translators']) && (count($contributors['translators']) > 0)) {
    $translators = $contributors['translators'];

    echo "<tr><td class='head' valign='top'>" . _AM_FLICKR_TEXT_TRANSLATORS . '</td>';

    echo "<td class='even' valign='top'>";

    echo "<table border='0'>";      //Create nested table for translators

    foreach ($translators as $tran) {
        if ('' != $tran['website']) {
            $tran_contact = "<a href='" . $tran['website'] . "'>" . $tran['uname'] . '</a>';
        } else {
            if ('' != $tran['email']) {
                $tran_contact = "<a href='mailto: " . $tran['email'] . "'>" . $tran['uname'] . '</a>';
            } else {
                $tran_contact = $tran['uname'];
            }
        }

        echo '<tr><td>' . $tran_contact . ' (' . $tran['language'] . ')</td>
                  </tr>';
    }

    echo '</table>';

    echo '</tr>';       //end of nested translators table
}

if (isset($contributors['testers']) && (count($contributors['testers']) > 0)) {
    $testers = $contributors['testers'];

    echo "<tr><td class='head' valign='top'>" . _AM_FLICKR_TEXT_TESTERS . '</td>';

    echo "<td class='even' valign='top'>";

    echo "<table border='0'>";      //Create nested table for testers

    foreach ($testers as $tester) {
        if ('' != $tester['website']) {
            $tester_contact = "<a href='" . $tester['website'] . "'>" . $tester['uname'] . '</a>';
        } else {
            if ('' != $tester['email']) {
                $tester_contact = "<a href='mailto: " . $tester['email'] . "'>" . $tester['uname'] . '</a>';
            } else {
                $tester_contact = $tester['uname'];
            }
        }

        echo '<tr><td>' . $tester_contact . ' (' . $tester['name'] . ')</td>
                  </tr>';
    }

    echo '</table>';

    echo '</tr>';       //end of nested testers table
}

if (isset($contributors['documenters']) && (count($contributors['documenters']) > 0)) {
    $documenters = $contributors['documenters'];

    echo "<tr><td class='head' valign='top'>" . _AM_FLICKR_TEXT_DOCUMENTER . '</td>';

    echo "<td class='even' valign='top'>";

    echo "<table border='0'>";      //Create nested table for documenters

    foreach ($documenters as $doc) {
        if ('' != $doc['website']) {
            $doc_contact = "<a href='" . $doc['website'] . "'>" . $doc['uname'] . '</a>';
        } else {
            if ('' != $doc['email']) {
                $doc_contact = "<a href='mailto: " . $doc['email'] . "'>" . $doc['uname'] . '</a>';
            } else {
                $doc_contact = $doc['uname'];
            }
        }

        echo '<tr><td>' . $doc_contact . ' (' . $doc['name'] . ')</td>
                  </tr>';
    }

    echo '</table>';

    echo '</tr>';       //end of nested documenters table
}

if (isset($contributors['code']) && (count($contributors['code']) > 0)) {
    $coders = $contributors['code'];

    echo "<tr><td class='head' valign='top'>" . _AM_FLICKR_TEXT_CODE . '</td>';

    echo "<td class='even' valign='top'>";

    echo "<table border='0'>";      //Create nested table for code contributors

    foreach ($coders as $coder) {
        if ('' != $coder['website']) {
            $coder_contact = "<a href='" . $coder['website'] . "'>" . $coder['uname'] . '</a>';
        } else {
            if ('' != $coder['email']) {
                $coder_contact = "<a href='mailto: " . $coder['email'] . "'>" . $coder['uname'] . '</a>';
            } else {
                $coder_contact = $coder['uname'];
            }
        }

        echo '<tr><td>' . $coder_contact . ' (' . $coder['name'] . ')</td>
                  </tr>';
    }

    echo '</table>';

    echo '</tr>';       //end of nested documenters table
}

echo '</table><br>';

echo "<table width='100%' border='0' cellspacing='1' class='outer'>";
echo "<tr><th colspan='2'>" . _AM_FLICKR_TEXT_MODULE_DEVELOPMENT . '</th></tr>';
echo "<tr><td class='head' width='20%' valign='top'>" . _AM_FLICKR_TEXT_RELEASE_DATE . "</td>
              <td class='even' valign='top'>" . $flickr_info->getInfo('release_date') . '</td>
          </tr>';
echo "<tr><td class='head' valign='top'>" . _AM_FLICKR_TEXT_STATUS . "</td>
              <td class='even' valign='top'>" . $flickr_info->getInfo('version_info') . '</td>
          </tr>';
echo "<tr><td class='head' valign='top'>" . _AM_FLICKR_TEXT_DEMO_SITE . "</td>
              <td class='even' valign='top'><a target='_blank' href='" . $flickr_info->getInfo('demo_site') . "'>" . _AM_FLICKR_DEMO_SITE . '</a></td>
          </tr>';
echo "<tr><td class='head' valign='top'>" . _AM_FLICKR_TEXT_OFFICIAL_SITE . "</td>
              <td class='even' valign='top'><a target='_blank' href='" . $flickr_info->getInfo('official_site') . "'>" . _AM_FLICKR_OFFICIAL_SITE . '</a></td>
          </tr>';
echo "<tr><td class='head' valign='top'>" . _AM_FLICKR_TEXT_REPORT_BUG . "</td>
              <td class='even' valign='top'><a target='_blank' href='" . $flickr_info->getInfo('bug_url') . "'>" . _AM_FLICKR_REPORT_BUG . '</a></td>
          </tr>';
echo "<tr><td class='head' valign='top'>" . _AM_FLICKR_TEXT_NEW_FEATURE . "</td>
              <td class='even' valign='top'><a target='_blank' href='" . $flickr_info->getInfo('feature_url') . "'>" . _AM_FLICKR_NEW_FEATURE . '</a></td>
          </tr>';
echo "<tr><td class='head' valign='top'>" . _AM_FLICKR_TEXT_QUESTIONS . "</td>
              <td class='even' valign='top'><a href='mailto: " . $flickr_info->getInfo('questions_email') . "'>" . _AM_FLICKR_QUESTIONS . '</a></td>
          </tr>';
echo '</table><br>';

echo "<table width='100%' border='0' cellspacing='1' class='outer'>";
echo '<tr><th>' . _AM_FLICKR_DISCLAIMER . '</th></tr>';
echo "<tr><td class='even'>" . _AM_FLICKR_DISCLAIMER_TEXT . '</td></tr>';
echo '</table><br>';

// For CHANGELOG
if (is_dir(FLICKR_BASE_PATH . '/docs')) {
    $dir = FLICKR_BASE_PATH . '/docs/';
} else {
    $dir = FLICKR_BASE_PATH;
}
$filename = 'CHANGELOG.txt';
if (is_file($dir . $filename)) {
    $filesize = filesize($dir . $filename);

    $handle = fopen($dir . $filename, 'rb');

    echo "<table width='100%' border='0' cellspacing='1' class='outer'>";

    echo '<tr><th>' . _AM_FLICKR_CHANGELOG . '</th></tr>';

    echo "<tr><td class='even'><pre>" . fread($handle, $filesize) . '</pre></td></tr>';

    echo '</table><br>';

    fclose($handle);
}
flickr_adminFooter();
xoops_cp_footer();
