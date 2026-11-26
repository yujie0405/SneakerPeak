<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Pop up</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
        <style>
          .popup-outer
        {
            display: flex;
            justify-content: center;
            width: 100%;
            height: 100%;
            background-color: rgb(44, 44, 44);
            position: absolute;
            z-index: 2;
            
        }
        .popup-box
        {
            background: var(--color-surface);
            width: 70%;
            height: 80%;
            position: relative;
            padding: 30px;
            border-radius: 12px;
            
            box-shadow: 0 .4rem .8rem #0005;
           
        }

       .popup-box i
       {
        float: right;
       }

       .inform
       {
        display: flex;
        padding-top: 30px;
        justify-content: space-between;
       }

       .popup-image
       {
        padding-top: 20px;
       }
        .popup-image img
        {
            height:80%;
            width: 70%;
            border-radius: 12px;
        }
        .cont
        {
            padding-right: 20%;
            font-family: Arial, Helvetica, sans-serif;
        }
        .cont table
        {
            border: 1px black solid;
            border-radius: 8px;
            width: 400px;
           
        }

        .cont table thead th
        {
            position: sticky;
            background-color: #d5d1defe;
            cursor: pointer;
            text-transform: capitalize;
        }

        .cont table td
        {
            text-align: center;
            height: 40px;
        }
        .status
        {
            background: greenyellow;
            color: black;
            font-weight: bolder;
            border-radius: 8px;
            display: inline-block; /* Added display property */
            padding: 8px 16px; /* Added padding for height */
            line-height: 1; /* Added line-height to center text vertically */

        }

        .button-status button
        {
            width: 80px;
            height: 40px;
            border-radius: 8px;
            background:rgb(78, 100, 246) ;
            color: white;
        }
        .button-status button:hover
        {
            background: rgb(14, 44, 244);
        }
        .button-status button:active{
            background: rgb(1, 13, 100);
        }
        </style>
    <?php require SNEAKERPEAK_ROOT . '/resources/views/layouts/theme.php'; ?>
</head>
    <body>
        <div class="popup-outer">
            <div class="popup-box">
                <i class="fa fa-times"></i>
                <div class="inform">
                    <div class="popup-image">
                        <img src="prod/AIR FORCE 1 07B1.jpg" alt="">
                    </div>
                    <div class="cont">
                        <h4>Product Name</h4>
                        <p>Product Details</p>
                        <table>
                            <thead>
                                <tr>
                                    <th>Size</th>
                                    <th>Qty</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>9</td>
                                    <td>1</td>
                                    <td><span class="status">Available</span></td>
                                </tr>
                                <tr>
                                    <td>9</td>
                                    <td>1</td>
                                    <td><span class="status">Available</span></td>
                                </tr>
                                <tr>
                                    <td>9</td>
                                    <td>1</td>
                                    <td><span class="status">Available</span></td>
                                </tr>
                                <tr>
                                    <td>9</td>
                                    <td>1</td>
                                    <td><span class="status">Available</span></td>
                                </tr>
                                <tr>
                                    <td>9</td>
                                    <td>1</td>
                                    <td><span class="status">Available</span></td>
                                </tr>
                            </tbody>
                        </table>
                        <h4>Status: Available</h4>
                        <div class="button-status">
                            <button>Deleted</button>
                            <button>Available</button>
                        </div>            
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>