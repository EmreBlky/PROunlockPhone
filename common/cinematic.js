function slideRetroCinematic(i,t){$(".iS-Cinematic").css({width:"",height:"",marginLeft:"",marginTop:""});var e=i;if(e.hasClass("iS-CinematicCenter")){var a=e.width(),h=e.height(),n=1.5*a,m=1.5*h,s=a*-.25,g=h*-.25,o=4e4;setTimeout(function(){e.animate({width:n,height:m,marginLeft:s,marginTop:g},o)},t)}else if(e.hasClass("iS-CinematicTop")){var a=e.width(),h=e.height(),n=1.5*a,m=1.5*h,s=a*-.25,g=0,o=4e4;setTimeout(function(){e.animate({width:n,height:m,marginLeft:s,marginTop:g},o)},t)}else if(e.hasClass("iS-CinematicBottom")){var a=e.width(),h=e.height(),n=1.5*a,m=1.5*h,s=a*-.25,g=h*-.5,o=4e4;setTimeout(function(){e.animate({width:n,height:m,marginLeft:s,marginTop:g},o)},t)}else if(e.hasClass("iS-CinematicLeft")){var a=e.width(),h=e.height(),n=1.5*a,m=1.5*h,s=0,g=h*-.25,o=4e4;setTimeout(function(){e.animate({width:n,height:m,marginLeft:s,marginTop:g},o)},t)}else if(e.hasClass("iS-CinematicRight")){var a=e.width(),h=e.height(),n=1.5*a,m=1.5*h,s=a*-.5,g=h*-.25,o=4e4;setTimeout(function(){e.animate({width:n,height:m,marginLeft:s,marginTop:g},o)},t)}else if(e.hasClass("iS-CinematicTopLeft")){var a=e.width(),h=e.height(),n=1.5*a,m=1.5*h,s=0,g=0,o=4e4;setTimeout(function(){e.animate({width:n,height:m,marginLeft:s,marginTop:g},o)},t)}else if(e.hasClass("iS-CinematicTopRight")){var a=e.width(),h=e.height(),n=1.5*a,m=1.5*h,s=a*-.5,g=0,o=4e4;setTimeout(function(){e.animate({width:n,height:m,marginLeft:s,marginTop:g},o)},t)}else if(e.hasClass("iS-CinematicBottomLeft")){var a=e.width(),h=e.height(),n=1.5*a,m=1.5*h,s=0,g=h*-.5,o=4e4;setTimeout(function(){e.animate({width:n,height:m,marginLeft:s,marginTop:g},o)},t)}else if(e.hasClass("iS-CinematicBottomRight")){var a=e.width(),h=e.height(),n=1.5*a,m=1.5*h,s=a*-.5,g=h*-.5,o=4e4;setTimeout(function(){e.animate({width:n,height:m,marginLeft:s,marginTop:g},o)},t)}}