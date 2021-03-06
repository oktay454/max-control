# Copyright (C) 2011 Mario Izquierdo (mariodebian) for Comunidad de Madrid
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License, version 2, as
# published by the Free Software Foundation.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

use strict;
use warnings;

package EBox::MaxControl;

use base qw(EBox::Module::Service
            EBox::FirewallObserver
            );

use EBox::Exceptions::DataExists;
use EBox::Gettext;
use EBox::Menu::Item;
use EBox::Service;
use EBox::Sudo qw ( :all );
use EBox::Validate qw ( :all );
use EBox::MaxControlFirewall;

sub _create
{
    my $class = shift;
    my $self = $class->SUPER::_create(name => 'maxcontrol',
                      printableName => 'MAX Control',
                      @_);
    bless($self, $class);
    return $self;
}

# Method: menu
#
#       Overrides EBox::Module method.
sub menu
{
    my ($self, $root) = @_;
    $root->add(new EBox::Menu::Item('url' => 'MaxControl/Index',
                                    'text' => $self->printableName(),
                                    'tag' => 'main',
                                    'icon' => 'samba',
                                    'order' => 4));
}

#sub _daemons
#{
#    return [
#        {
#            'name' => 'backharddi-ng',
#            'type' => 'init.d',
#            'pidfiles' => ['/var/run/backharddi-ng.pid']
#        }
#    ];
#}

sub usesPort # (protocol, port, iface)
{
    my ($self, $protocol, $port, $iface) = @_;

    return undef unless($self->isEnabled());

    return 1 if ($port eq 80);
    # return 1 if ($port eq 389);

    return undef;
}

sub firewallHelper
{
    my ($self) = @_;
    if ($self->isEnabled()){
        return new EBox::MaxControlFirewall();
    }
    return undef;
}

1;
