// Views
var PluginMessagesCreateModal = Backbone.View.extend({

	tagName: "article",
	className: "modal",

	initialize: function(options) {
		options.recipient = options.recipient || '';
		options.subject   = options.subject || '';
		this.template = _.template($("#plugin-messages-create-modal-template").html(), options);
		this.$el.html(this.template);
	},

	events: {
		"click .send a": "sendMessage",
		"click .close a": "closeModal",
		//"focus #message-recipient": "lookup",
		"keyup #message-recipient": "lookup",
		"focusout #message-recipient": "clearLookup"
	},

	sendMessage: function() {
		var message = new PluginMessagesModel({
			recipient: this.$('#message-recipient').val(),
			subject: this.$('#message-subject').val(),
			body: this.$('#message-body').val()
		});
		message.save({},{
			success: function(model, response) {
				$('p.message-status').css('color', 'green');
				$('p.message-status').text(response);
				modalHide();
			},
			error: function(model, response) {
				$('p.message-status').css('color', 'red');
				$('p.message-status').text($.parseJSON(response.responseText));
			},
		});
		return false;
	},
	closeModal: function() {
		modalHide();
		return false;
	},
	lookup: function() {
		if (this.timer) {
			clearTimeout(this.timer);
		}
		this.timer = setTimeout(function() {
			var query = $.trim(this.$("#message-recipient").val());
			if (query) {
				var _this = this;
				$.getJSON(this.$('#lookup-url').val(), {q: query}, function(response) {
					var items = [];
					$.each(response, function(key, val) {
						items.push('<li id="'+val.account_path+'">'+val.name+'</li>');
					});
					if (items.length > 0) {
						_this.$('#recipient-lookup').css('display', 'block');
					}
					_this.$('#recipient-lookup').html(items.join(''));
					_this.$('#recipient-lookup li').click(function() {
						_this.$('#recipient-lookup').css('display', 'none');
						_this.$("#message-recipient").val(_this.$('#recipient-lookup li').attr('id'));
					});
				});
			}
			this.timer = null;
		}, 500);
	},
	clearLookup: function() {
		setTimeout(function() {
			if ( ! this.$('#recipient-lookup').is(":focus")) {
				this.$('#recipient-lookup').html('');
				this.$('#recipient-lookup').css('display', 'none');
			}
		}, 200);
	}
});

// Models
var PluginMessagesModel = Backbone.Model.extend({

	url: function() {
		return $('#message-url').val();
	},
});

$(function() {
	// Click events
	$('.messages-title a.create').click(function(){
		modalShow(new PluginMessagesCreateModal({}).render().el);
		return false;
	});
});
