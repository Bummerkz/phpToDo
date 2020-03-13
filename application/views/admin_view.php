<h3>Добавить</h3>

<div class="row">
    <form class="col s12" action="admin/add" method="POST">
        <div class="form-row">
            <div class="form-group col-md-6">
                <input type="text" class="form-control" placeholder="Ваше имя" aria-label="Username" name="username" aria-describedby="basic-addon1" required>
            </div>

            <div class="form-group col-md-6">
                <input type="email" class="form-control" placeholder="Email" aria-label="Username" name="email" aria-describedby="basic-addon1" required>
            </div>
        </div>
        <div class="input-group">
            <textarea class="form-control" placeholder="Текст задачи" name="text" aria-label="With textarea" required></textarea>
        </div>
        <br />
        <input type="hidden" id="sort" value="asc" name="sort">
        <input type="hidden" id="columnName" value="username" name="columnName">

        <div class="input-group">
            <button class="btn btn-primary" type="submit">Добавить</button>
        </div>
        <br />
    </form>
</div>

<div id="content-table" height="500">
    <table width="100%" id="tasks" class="table table-hover">
        <thead class="thead-dark">
            <tr>
                <th width="15" scope="col"><span onclick='sortTable("username");'>Имя пользователя</span></th>
                <th width="15"><span onclick='sortTable("email");'>Email</span></th>
                <th>Текст задачи</th>
                <th width="10"><span onclick='sortTable("status");'>Статус</th>
                <th width="10">Удалить</th>
            </tr>
        </thead>
    </table>
</div>

<div id="div_pagination">
    <input type="hidden" id="page" value="1">
    <input type="hidden" id="count" value="0">
    <input type="button" class="btn btn-primary" value="Назад" id="but_prev" />
    <input type="button" class="btn btn-primary" value="Вперёд" id="but_next" />
</div>

<script type="text/javascript">
    var limit = 3; // количество задач на странице
    var columnName = ''; // инициализация сортировки
    var sort = ''; // инициализация сортировки
    var textChanged = false; // статус изменения текста админом
    var tempText = ''; // временное хранение текста задачи, используется для отслеживания изменения текста админом

    $(document).ready(function() {
        columnName = $("#columnName").val();
        sort = $('#sort').val();

        getData(columnName, sort)

        // Редактирование текста задачи
        $(document).on('keyup', '.edit', function() {
            if (tempText === $(this).text()) {
                textChanged = false;
            } else {
                textChanged = true;
            }
        });

        $(document).on('click', '.edit', function() {
            tempText = $(this).text();
        });

        $(document).on('focusout', '.edit', function() {

            if (textChanged) {
                var id = $(this).attr('id');
                var text = $(this).text();
                $(this).closest('tr').addClass('table-warning');
                $.ajax({
                    url: 'admin/update',
                    type: 'post',
                    data: {
                        id: id,
                        text: text
                    },
                    success: function(response) {
                        if (response == 'true') {
                            console.log('Save successfully');
                        } else {
                            console.log('Problem!');
                            location.reload();
                        }
                    }
                });
            }
        });

        // Изменение статуса задачи
        $(document).on('click', '.editCheckbox', function() {
            var id = $(this).attr('id');
            var status = $(this).is(':checked');
            var adminrev = $(this).closest('tr').hasClass('table-warning');

            if (status) {
                if (!adminrev) {
                    $(this).closest('tr').addClass('table-success');
                }
            } else {
                if (!adminrev) {
                    $(this).closest('tr').removeClass('table-success');
                }
            }

            $.ajax({
                type: 'POST',
                url: 'admin/updatestatus',
                data: {
                    id: id,
                    status: status
                },
                success: function(response) {
                    if (response = 'true') {
                        console.log('Save successfully');
                    } else {
                        console.log('Problem!');
                    }
                }
            });
        });

        // Удаление задачи
        $(document).on('click', '.del', function() {
            var id = $(this).attr('id');

            $.ajax({
                type: 'POST',
                url: 'admin/delete',
                data: {
                    id: id
                },
                success: function(response) {
                    if (response == 'true') {
                        alert("Задача удалена");
                        getData(columnName, sort);
                    } else {
                        alert("Что-то пошле не так!");
                        location.reload();
                    }
                }
            });
        });

        // Кнопка назад
        $("#but_prev").click(function() {
            var page = Number($("#page").val());
            var count = Number($("#count").val());
            columnName = $('#columnName').val();
            sort = $('#sort').val();

            page -= 1;

            if (page < 1) {
                page = 1;
                $("#page").val(page);
            } else {
                $("#page").val(page);
                getData(columnName, sort);
            }
        });

        // Кнопка вперёд
        $("#but_next").click(function() {
            var page = Number($("#page").val());
            var count = Number($("#count").val());
            columnName = $('#columnName').val();
            sort = $('#sort').val();

            page += 1;

            if (page <= Math.ceil(count / limit)) {
                $("#page").val(page);
                getData(columnName, sort);
            }
        });
    });

    // Сортировка таблицы
    function sortTable(columnName) {
        $("#columnName").val(columnName);
        sort = $('#sort').val();

        getData(columnName, sort);

        if (sort == "asc") {
            $("#sort").val("desc");
        } else {
            $("#sort").val("asc");
        };
    }

    // Получаем список задач из базы данных в виде json
    function getData(columnName, sort) {
        var page = $("#page").val();
        var count = $("#count").val();

        $.ajax({
            url: 'admin/getdata',
            type: 'post',
            data: {
                page: page,
                columnName: columnName,
                sort: sort
            },
            dataType: 'json',
            success: function(response) {
                createTablerow(response);
            }
        });
    }

    // Создаем таблицу со списком задач
    function createTablerow(data) {

        var dataLen = data.length;

        $("#tasks tr:not(:first)").remove();

        for (var i = 0; i < dataLen; i++) {
            if (i == 0) {
                var count = data[i]['count'];
                $("#count").val(count);
            } else {
                var id = data[i]['id'];
                var username = data[i]['username'];
                var email = data[i]['email'];
                var text = data[i]['text'];
                var btn_del = '<span id=' + id + ' class="del">X</span>'

                if (data[i]['status'] == '1') {
                    var status = '<input class = "editCheckbox" type="checkbox" id="' + id + '" checked/>';
                    var statusClass = ' table-success'
                } else {
                    var status = '<input class = "editCheckbox" type="checkbox" id="' + id + '" />';
                    var statusClass = ''
                }

                if (data[i]['adminrev'] == '1') {
                    var adminrevClass = 'table-warning';
                } else {
                    var adminrevClass = '';
                }

                taskEdit = "<div contentEditable='true' class='edit' id=" + id + ">" + text + "</div>"

                $("#tasks").append("<tr id='tr_" + i + "' class = '" + adminrevClass + statusClass + "'></tr>");
                $("#tr_" + i).append("<td class='td'>" + username + "</td>");
                $("#tr_" + i).append("<td class='td'>" + email + "</td>");
                $("#tr_" + i).append("<td>" + taskEdit + "</td>");
                $("#tr_" + i).append("<td class='td'>" + status + "</td>");
                $("#tr_" + i).append("<td class='td'>" + btn_del + "</td>");
            }
        }
    }
</script>