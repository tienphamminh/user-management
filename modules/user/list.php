<?php

if (!defined('_INCODE')) die('Access Denied...');


$dataHeader = [
    'pageTitle' => 'List Users'
];
addLayout('header', $dataHeader);

// Search form handling
$conditionalClause = '';
$dataCondition = [];
if (isGet()) {
    $body = getBody();
    if (!empty($body['status'])) {
        $status = $body['status'];
        if ($status == 2) {
            $dbStatus = 0;
        } else {
            $dbStatus = 1;
        }
        $conditionalClause .= "WHERE status=:status";
        $dataCondition['status'] = $dbStatus;
    }

    if (!empty($body['keyword'])) {
        $keyword = $body['keyword'];
        if (str_contains($conditionalClause, 'WHERE')) {
            $operator = ' AND';
        } else {
            $operator = 'WHERE';
        }
        $conditionalClause .= "$operator CONCAT_WS('|', fullname, email) LIKE :search";
        $search = "%$keyword%";
        $dataCondition['search'] = $search;
    }
}

// Pagination
// Set the limit of number of records to be displayed per page
$limit = 3;

// Determine the total number of pages available
$sql = "SELECT id FROM user $conditionalClause";
$numberOfResults = getNumberOfRows($sql, $dataCondition);
$numberOfPages = ceil($numberOfResults / $limit);

// Determine the current page number
if (!empty(getBody()['page'])) {
    $pageNumber = getBody()['page'];
    if ($pageNumber < 1 || $pageNumber > $numberOfPages) {
        $pageNumber = 1;
    }
} else {
    $pageNumber = 1;
}

// Calculating the OFFSET from page number
$offset = ($pageNumber - 1) * $limit;

// Retrieve data
$sql = "SELECT * FROM user $conditionalClause ORDER BY fullname LIMIT :limit OFFSET :offset";
$users = getLimitRows($sql, $limit, $offset, $dataCondition);


if (!empty($_SERVER['QUERY_STRING'])) {
    $queryString = $_SERVER['QUERY_STRING'];
    $queryString = str_replace('module=user&action=list', '', $queryString);
    $queryString = str_replace('&page=' . $pageNumber, '', $queryString);
}

$msg = getFlashData('msg');
$msgType = getFlashData('msg_type');
?>
    <div class="container" style="margin-top: 40px">
        <h3>User Management System</h3>
        <p>
            <a href="?module=user&action=add" class="btn btn-success">
                Add user <i class="fa fa-plus"></i>
            </a>
        </p>
        <form action="" method="get">
            <input type="hidden" name="module" value="user">
            <input type="hidden" name="action" value="list">
            <div class="row">
                <div class="col-3">
                    <div class="form-group">
                        <select name="status" class="form-control">
                            <option value="0">Choose Status</option>
                            <option value="1" <?php echo (!empty($status) && $status == 1) ? 'selected' : null; ?>>
                                Active
                            </option>
                            <option value="2" <?php echo (!empty($status) && $status == 2) ? 'selected' : null; ?>>
                                Not Active
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-7">
                    <input type="search" class="form-control" name="keyword"
                           placeholder="Search by fullname or email..."
                           value="<?php echo (!empty($keyword)) ? $keyword : null; ?>">
                </div>
                <div class="col-2">
                    <button type="submit" class="btn btn-primary btn-block">Search</button>
                </div>
            </div>
            <p class="text-muted"><?php echo 'Total: ' . $numberOfResults . ' rows.'; ?></p>
        </form>

        <?php echo getMessage($msg, $msgType); ?>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th style="width: 5%">No.</th>
                <th>Fullname</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Status</th>
                <th style="width: 5%">Edit</th>
                <th style="width: 5%">Delete</th>
            </tr>
            </thead>

            <tbody>
            <?php
            if (!empty($users)):
                $ordinalNumber = $offset;

                foreach ($users as $user):
                    $ordinalNumber++;
                    ?>
                    <tr>
                        <td><?php echo $ordinalNumber; ?></td>
                        <td><?php echo $user['fullname']; ?></td>
                        <td><?php echo $user['email']; ?></td>
                        <td><?php echo $user['phone']; ?></td>
                        <td>
                            <?php
                            if ($user['status'] == '1') {
                                echo '<button type="button" class="btn btn-success btn-sm">Active</button>';
                            } else {
                                echo '<button type="button" class="btn btn-warning btn-sm">Not Active</button>';
                            }
                            ?>
                        </td>
                        <td>
                            <a href="?module=user&action=edit&id=<?php echo $user['id']; ?>"
                               class="btn btn-warning btn-sm">
                                <i class="fa fa-edit"></i>
                            </a>
                        </td>
                        <td>
                            <form action="?module=user&action=delete" method="post">
                                <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure?')">
                                    <i class="fa fa-trash-o"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php
                endforeach;
            else:
                ?>
                <tr>
                    <td colspan="7">
                        <div class="alert alert-danger text-center">No data to display.</div>
                    </td>
                </tr>
            <?php
            endif;
            ?>
            </tbody>
        </table>

        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <?php
                // Display Previous button
                if ($pageNumber > 1) {
                    $prevPage = $pageNumber - 1;
                    echo '<li class="page-item"><a class="page-link" href="?module=user&action=list&page=' . $prevPage . $queryString . '">Previous</a></li>';
                }
                ?>

                <?php
                $numberOfItems = 5;
                $half = floor($numberOfItems / 2);
                $begin = $pageNumber - $half;
                $end = $pageNumber + $half;
                if ($numberOfPages <= $numberOfItems) {
                    $begin = 1;
                    $end = $numberOfPages;
                } else {
                    if ($begin < 1) {
                        $begin = 1;
                        $end = $numberOfItems;
                    }
                    if ($end > $numberOfPages) {
                        $begin = $numberOfPages - $numberOfItems + 1;
                        $end = $numberOfPages;
                    }
                }
                // Display page-item
                for ($index = $begin; $index <= $end; $index++) {
                    ?>
                    <li class="page-item <?php echo ($index == $pageNumber) ? 'active' : null; ?>">
                        <a class="page-link"
                           href="?module=user&action=list&page=<?php echo $index . $queryString; ?>">
                            <?php echo $index; ?>
                        </a>
                    </li>
                    <?php
                }
                ?>

                <?php
                // Display Next button
                if ($pageNumber < $numberOfPages) {
                    $nextPage = $pageNumber + 1;
                    echo '<li class="page-item"><a class="page-link" href="?module=user&action=list&page=' . $nextPage . $queryString . '">Next</a></li>';
                }
                ?>
            </ul>
        </nav>
        <hr/>
    </div>
<?php
addLayout('footer');
