require(
	{
		baseUrl: '//' + window.location.host + '/_Resources/Static/Packages/',
		urlArgs: 'bust=' + (new Date()).getTime(),
		paths: {
			'jquery': 'Beech.Ehrm/JavaScript/jquery',
			'jquery-lib': 'Beech.Ehrm/Library/jquery-ui/js/jquery-1.7.2.min',
			'jquery.ui': 'Beech.Ehrm/Library/jquery-ui/js/jquery-ui-1.8.21.custom.min',
			'emberjs': 'Emberjs/Core/minified/ember-0.9.8.min',
			'bootstrap': 'Twitter.Bootstrap/2.0/js/bootstrap.min',
			'notification': 'Beech.Ehrm/JavaScript/Notification',
			'ui': 'Beech.Ehrm/JavaScript/UserInterface'
		},
		shim: {
			'jquery-lib': {
				'exports': 'jQuery'
			},
			'jquery.ui': ['jquery'],
			'bootstrap': ['jquery'],
			'emberjs': {
				'deps': ['jquery'],
				'exports': 'Ember'
			}
		}
	},
	[
		'jquery',
		'emberjs',
		'jquery.ui',
		'bootstrap'
	],
	function(jQuery, Ember) {

		jQuery(document).ready(function() {

			require(['ui', 'notification'], function(UserInterface, Notification) {
				window.App = Ember.Application.create({
					ready: function() {
						UserInterface.modal().initialize();
						Notification.initialize();
					}
				});
			});
		});
	}
);