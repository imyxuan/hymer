<style>
    .hymer .compass .nav-tabs {
        background: none;
        border-bottom: 0;
    }

    .hymer .compass .nav-tabs > li {
        margin-bottom: -1px !important;
    }

    .hymer .compass .nav-tabs a i {
        display: block;
        font-size: 22px;
    }

    .tab-content {
        padding: 20px;
        background: #ffffff;
        border: 1px solid transparent;
        border-radius: 4px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .page-title {
        z-index: 2;
    }

    .page-content {
        z-index: 2;
        position: relative;
    }

    .page-title p {
        margin-bottom: 0;
        padding-top: 0;
        line-height: 1;
    }

    .page-title span {
        font-size: 12px;
        font-weight: normal;
        line-height: 1;
    }

    #gradient_bg {
        position: fixed;
        top: 61px;
        left: 0;
        background-image: url({{ hymer_asset('images/bg.jpg') }});
        background-size: cover;
        background-position: 0;
        width: 100%;
        height: 220px;
        z-index: 0;
    }

    #gradient_bg::after {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        /* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#f8f8f8+0,f8f8f8+100&0.95+0,1+80 */
        background: -moz-linear-gradient(top, rgba(248, 248, 248, 0.93) 0%, rgba(248, 248, 248, 1) 80%, rgba(248, 248, 248, 1) 100%); /* FF3.6-15 */
        background: -webkit-linear-gradient(top, rgba(248, 248, 248, 0.93) 0%, rgba(248, 248, 248, 1) 80%, rgba(248, 248, 248, 1) 100%); /* Chrome10-25,Safari5.1-6 */
        background: linear-gradient(to bottom, rgba(248, 248, 248, 0.93) 0%, rgba(248, 248, 248, 1) 80%, rgba(248, 248, 248, 1) 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#f2f8f8f8', endColorstr='#f8f8f8', GradientType=0); /* IE6-9 */
        z-index: 1;
    }

    .nav-tabs > li.active > a, .nav-tabs > li.active > a:focus, .nav-tabs > li.active > a:hover {
        background: #fff !important;
        color: #62a8ea !important;
        border-bottom: 1px solid #fff !important;
        top: -1px !important;
    }

    .nav-tabs > li a {
        transition: all 0.3s ease;
    }


    .nav-tabs > li.active > a:focus {
        top: 0 !important;
    }

    .hymer-link {
        width: 100%;
        min-height: 220px;
        display: block;
        border-radius: 3px;
        background-position: center center;
        background-size: cover;
        position: relative;
    }

    .hymer-link span.resource_label {
        text-align: center;
        color: #fff;
        display: block;
        position: absolute;
        z-index: 9;
        top: 0;
        left: 0;
        width: 100%;
        padding: 0;
        opacity: 0.8;
        transition: all 0.3s ease;
        line-height: 220px;
        height: 100%;
    }

    .hymer-link span.resource_label:hover {
        opacity: 1;
    }

    .hymer-link i {
        font-size: 48px;
        margin-right: 0;
        position: absolute;
        width: 70px;
        height: 70px;
        padding: 10px;
        border-radius: 5px;
        line-height: 55px;
        display: inline-block;
        left: 50%;
        margin-top: -50px;
        margin-left: -35px;
        top: 50%;
    }

    .hymer-link span.resource_label:hover i {

        opacity: 1;
        transition: all 0.3s linear;
    }

    .hymer-link span.copy {
        position: absolute;
        font-size: 16px;
        left: 0;
        bottom: 70px;
        line-height: 12px;
        text-transform: uppercase;
        text-align: center;
        width: 100%;
    }

    h3 small {
        font-size: 11px;
        position: relative;
        top: -3px;
        left: 10px;
        color: #999;
    }

    .accordion-collapse {
        background: #fcfcfc;
    }

    .hymer-link span.desc {
        font-size: 11px;
        color: rgba(255, 255, 255, 0.8);
        width: 100%;
        height: 100%;
        position: absolute;
        text-align: center;
    }

    .hymer-angle-down {
        display: none;
    }

    .hymer-link::after {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        background: -moz-linear-gradient(-65deg, rgba(17, 17, 17, 0.7) 0%, rgba(35, 35, 47, 0.7) 50%, rgba(17, 17, 23, 0.7) 51%, rgba(17, 17, 23, 0.7) 100%); /* FF3.6-15 */
        background: -webkit-linear-gradient(-65deg, rgba(17, 17, 17, 0.7) 0%, rgba(35, 35, 47, 0.7) 50%, rgba(17, 17, 23, 0.7) 51%, rgba(17, 17, 23, 0.7) 100%); /* Chrome10-25,Safari5.1-6 */
        background: linear-gradient(155deg, rgba(17, 17, 17, 0.7) 0%, rgba(35, 35, 47, 0.7) 50%, rgba(17, 17, 23, 0.7) 51%, rgba(17, 17, 23, 0.7) 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#b3111111', endColorstr='#b3111117', GradientType=1); /* IE6-9 fallback on horizontal gradient */
        left: 0;
        top: 0;
        border-radius: 3px;
    }

    #fonts ul {
        list-style: none;
        display: flex;
        padding-left: 10px;
        flex-wrap: wrap;
        justify-content: flex-start;
    }

    #fonts .icon {
        float: left;
        padding: 2px;
        font-size: 20px;
        padding-right: 10px;
        position: absolute;
        left: 0;
    }

    #fonts li {
        flex: 1;
        max-width: 212px;
        padding: 10px;
        padding-left: 30px;
        position: relative;
    }

    #fonts .hymer-angle-down {
        display: block;
    }

    #fonts h2 {
        font-size: 12px;
        font-weight: bold;
        padding: 0 20px 20px 5px;
    }

    #fonts h2:nth-child(2) {
        padding-top: 20px;
    }

    #fonts input {
        border-radius: 3px;
        border: 1px solid #f1f1f1;
        padding: 3px 7px;
    }

    #logs .level .glyphicon {
        float: left;
    }

    #logs table.dataTable thead th.sorting:after {
        top: 13px;
    }

    #logs table.dataTable thead .sorting_asc:after {
        top: 12px;
    }

    #logs .table-container {
        margin-top: 20px;
    }

    #logs .list-group {
        margin-top: 42px;
        border: 1px solid #ececec;
        border-right: 0;
        border-radius: 3px;
    }

    #logs .list-group-item {
        background: #ffffff;
        border: 0;
        border-radius: 0;
        font-size: 12px;
        font-weight: normal;
        color: #97999C;
        border-right: 1px solid #ececec;
    }

    .hymer #logs .table > thead > tr > th:first-child {
        border-top-left-radius: 3px;
    }

    .hymer #logs .table > thead > tr > th:last-child {
        border-top-right-radius: 3px;
    }

    #logs .list-group-item i {
        position: relative;
        top: 2px;
        left: -3px;
    }

    #logs .list-group-item.llv-active {
        background: #F9FCFF;
        font-weight: bold;
        position: relative;
    }

    #logs .list-group-item:hover {
        background: #F9FCFF;
    }

    #logs .list-group-item.llv-active:after, #logs .list-group-item.llv-active:before, #logs .list-group-item:hover:after, #logs .list-group-item:hover:before {
        content: '';
        position: absolute;
        width: 100%;
        height: 1px;
        left: 0;
        background: rgb(241, 241, 241); /* Old browsers */
        background: -moz-linear-gradient(left, rgba(241, 241, 241, 1) 0%, rgba(255, 255, 255, 1) 100%); /* FF3.6-15 */
        background: -webkit-linear-gradient(left, rgba(241, 241, 241, 1) 0%, rgba(255, 255, 255, 1) 100%); /* Chrome10-25,Safari5.1-6 */
        background: linear-gradient(to right, rgba(241, 241, 241, 1) 0%, rgba(255, 255, 255, 1) 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#f1f1f1', endColorstr='#ffffff', GradientType=1); /* IE6-9 */
    }

    #logs .list-group-item.llv-active:before, #logs .list-group-item:hover:before {
        top: 0;
    }

    #logs .list-group-item.llv-active:after, #logs .list-group-item:hover:after {
        bottom: 0;
        z-index: 9;
    }

    #command_lists {
        display: flex;
        flex-wrap: wrap;
    }

    #commands h3 {
        width: 100%;
        clear: both;
        margin-bottom: 20px;
    }

    #commands h3 i {
        position: relative;
        top: 3px;
    }

    #commands .command {
        padding: 10px;
        border: 1px solid #f1f1f1;
        border-radius: 4px;
        border-bottom: 2px solid #f5f5f5;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        padding-top: 30px;
        padding-right: 52px;
        flex: 1;
        min-width: 275px;
        margin: 10px;
        margin-left: 0;
    }

    #commands .command.more_args {
        padding-bottom: 40px;
    }

    #commands .command i {
        position: absolute;
        right: 4px;
        top: -6px;
        font-size: 45px;
    }

    #commands code {
        color: #549DEA;
        padding: 4px 7px;
        font-weight: normal;
        font-size: 12px;
        background: #f3f7ff;
        border: 0;
        position: absolute;
        top: 0;
        left: 0;
        border-bottom-left-radius: 0;
        border-top-right-radius: 0;
    }

    #commands .command:hover {
        border-color: #eaeaea;
        border-bottom-width: 2px;
    }


    .cmd_form {
        display: none;
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
    }

    .cmd_form input[type="text"], .cmd_form input[type="submit"] {
        width: 30%;
        float: left;
        margin: 0;
        font-size: 12px;
    }

    .cmd_form input[type="text"] {
        line-height: 30px;
        padding-top: 0;
        padding-bottom: 0;
        height: 30px;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        border-top-left-radius: 0;
        padding-left: 5px;
        font-size: 12px;
        width: 70%;
    }

    .cmd_form .form-control.focus, .cmd_form .form-control:focus {
        border-color: #eee;
    }

    .cmd_form input[type="submit"] {
        border-top-right-radius: 0;
        border-bottom-left-radius: 0;
        border-top-left-radius: 0;
        font-size: 10px;
        padding-left: 7px;
        padding-right: 7px;
        height: 30px;
    }


    #commands pre {
        background: #323A42;
        color: #fff;
        width: 100%;
        margin: 10px;
        margin-left: 0;
        padding: 15px;
        padding-top: 0;
        padding-bottom: 0;
        position: relative;
    }

    #commands .close-output {
        position: absolute;
        right: 15px;
        top: 15px;
        color: #ccc;
        cursor: pointer;
        padding: 5px 14px;
        background: rgba(0, 0, 0, 0.2);
        border-radius: 25px;
        transition: all 0.3s ease;
    }

    #commands .close-output:hover {
        color: #fff;
        background: rgba(0, 0, 0, 0.3);
    }

    #commands pre i:before {
        position: relative;
        top: 3px;
        right: 5px;
    }

    #commands pre .art_out {
        width: 100%;
        display: block;
        color: #98cb00;
        margin-bottom: 10px;
    }
</style>
