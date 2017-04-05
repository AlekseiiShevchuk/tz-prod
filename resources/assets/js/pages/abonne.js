/**
 * Created by Andrew Karpich on 09.02.2017.
 */

module.exports = {

    init: function(){
        this.add_chart(D('#chart').attr('percent'));
    },

    add_chart: function(num){
        let drawingCanvas = document.getElementById('chart');
        if(drawingCanvas && drawingCanvas.getContext){
            let context = drawingCanvas.getContext('2d');

            context.fillStyle = "#cdcdcd";
            context.beginPath();
            context.arc(100, 100, 94, 0, Math.PI * 2, true);
            context.closePath();
            context.fill();

            context.fillStyle = "#fff";
            context.strokeStyle = "#fff";
            context.beginPath();
            context.arc(100, 100, 93, 0, Math.PI * 2, true);
            context.closePath();
            context.fill();

            context.fillStyle = "#4e69b0";
            context.beginPath();
            context.moveTo(100, 100);
            let start = Math.PI * 1.5;
            context.arc(100, 100, 93, start, start + (Math.PI / 180) * num * 360 / 100, false);
            context.closePath();
            context.fill();
        }
    }
};