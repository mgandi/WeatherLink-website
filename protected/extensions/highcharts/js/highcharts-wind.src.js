// JavaScript Document
(function (H) {
	var merge = Highcharts.merge,
	Series = Highcharts.Series,
	seriesTypes = Highcharts.seriesTypes,
	extendClass = Highcharts.extendClass,
	defaultPlotOptions = Highcharts.getOptions().plotOptions,
	svgRenderer = Highcharts.SVGRenderer.prototype;
	svgRendererSymbols = svgRenderer.symbols;

/**
 * Check for object
 * @param {Object} obj
 */
function isObject(obj) {
	return typeof obj === 'object';
}
	
/* ****************************************************************************
 * Start Wind series code											          *
 *****************************************************************************/

// 1 - set default options
defaultPlotOptions.wind = merge(defaultPlotOptions.bubble, {
/*	
	tooltip: {
		pointFormat: '({point.x}, {point.y}), Direction: {point.z}'
	},
*/
});

// 2 - Add the flag symbol renderer functions
svgRendererSymbols.flag = function drawFlag(a,b,c,d,angle) {
	if (isObject(angle)) {
		angle = angle.angle;
	}
	
	var e=0.166*c,
	hyp = d*2.5,
	rad = (90 - angle) * Math.PI / 180,
	op = Math.sin(rad) * hyp,
	adj = Math.cos(rad) * hyp,
	xc = a+c/2, yc = b+d/2,
	xq = xc + adj, yq = yc - op;
	
	return["M",xc,yc,"L",xq,yq,"L",xc,yc,"Z","M",a+c/2,b,"C",a+c+e,b,a+c+e,b+d,a+c/2,b+d,"C",a-e,b+d,a-e,b,a+c/2,b,"Z"];
};


/**
 * Draw and return a wind flag
 * @param {Number} x X position
 * @param {Number} y Y position
 * @param {Number} r Radius
 * @param {Number} d Orientation in degrees
 */
svgRenderer.flag = function (x, y, r, d) {
		var flag;

		if (isObject(x)) {
			y = x.y;
			r = x.r;
			d = x.angle;
			x = x.x;
		}

		flag = this.symbol('flag', x || 0, y || 0, r || 0, r || 0, {
			angle: d,
		});
		return flag;
	},


// 3 - Create the series object
seriesTypes.wind = extendClass(seriesTypes.bubble, {
	type: 'wind',
	
	animate: seriesTypes.line.prototype.animate,
	
	/**
	 * Extend the base translate method to handle flag orientation
	 */
	translate: function () {
		
		var series = this,
			i,
			data = this.data,
			point,
			radius = series.options.marker.radius;
		
		// Run the parent's parent method
		seriesTypes.scatter.prototype.translate.call(this);
		
		// Set the shape type and arguments to be picked up in drawPoints
		i = data.length;
		
		while (i--) {
			point = data[i];

			// Flag for negativeColor to be applied in Series.js
			point.negative = point.z < (this.options.zThreshold || 0);
			
			// Shape arguments
			point.shapeType = 'flag';
			point.shapeArgs = {
				x: point.plotX - radius/2,
				y: point.plotY - radius/2,
				angle: point.z,
				r: radius,
			};
			
			/*
			// Alignment box for the data label
			point.dlBox = {
				x: point.plotX - radius,
				y: point.plotY - radius,
				width: 2 * radius,
				height: 2 * radius
			};
			*/
		}
	},
	
	convertAttribs: Series.prototype.convertAttribs,
});

/* ****************************************************************************
 * End Wind series code                                                     *
 *****************************************************************************/

}(Highcharts));
