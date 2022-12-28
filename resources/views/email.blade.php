<!-- <h1>Email Verification Mail</h1>
Please verify your email with bellow link: 
<a href="{{$link}}">{{$link}}</a> -->



<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Email Component</title>
<style>
      .head {
        background-color: black;
        height: 142px;
        margin-top: -20px;
        text-align: center;
      }
      .text{
        margin-top: 40px;
        text-align: center;
        margin-bottom: 25px;
        
      }
      .foot {
        display: flex;
        justify-content: center;
        padding: 5px;
        background-color:antiquewhite;
        color: #fff;
      }
      .imgg{
        color: #fff;
        padding-top: 23px;
        font-size: 60px;
      }

      .site-footer{
       background-color:#26272b;
       padding:45px 0 20px;
       font-size:15px;
       line-height:24px;
       color: white;
       }
       .btn{
        padding: 15px;
        padding-left:40px ;
        padding-right: 40px;
        background-color: #42423E;
        border: 0px;
        color: #fff;
        border-radius: 10px;
       }
       .cr-text{
        margin-top: -30px;
        text-align: center;
       }
      .cr-text1{
        margin-top: -15px;
        text-align: center;
       }
       .cr-text2{
        margin-top: -15px;
        text-align: center;
        font-size: smaller;
       }


    </style>
</head>
<body>
<header>
<div class="head">
<h1 class="imgg">Depiction</h1>
</div>
</header>
<div class="text">
<p>
Hi... We are happy to see you here.
</p>
<p>
Depiction provides authenticated Login system.
</p>
<p>
Dear [Name]
</p>
<p>
We are happy that you have signing up for our serivces.
</p>
<p>
We just need you to take one more step to get started. Please
click the button below to verify your email address:
</p>
<button class="btn">
<a href="{{$link}}">CLICK HERE</a>
    </button>
<p>If you didn't create an account with us, or don't want to
proceed, please ignore this email.
</p>
<p>Thanks again for joining us!
</p>
<p>Sincerely,</p>
<p>Depiction Private Limited</p>
</p>
</div>
<footer class="site-footer">
<br />
<div>
<div>
<div>
<p class="cr-text">Copyright &copy; 2022 All
Rights Reserved by Depiction
</p>
<p class="cr-text1">
Developed by:
</p>
<p class="cr-text2">
Faheem Khan | Ali Hamza | Taimmor Ali | Usama Hanan | Umair Azeem | Ali Rizwan
</p>
</div>
</div>
</div>
</footer>
</body>
</html>
