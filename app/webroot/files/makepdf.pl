#!/usr/bin/perl

use strict;
use warnings;
use PHP::Serialization qw(serialize unserialize);
use PDF::Reuse;

my $srcfile = $ARGV[0];
my $datafile = $ARGV[1];

#init new file
prFile();
open(my $df, '<', $datafile) or die "Could not open data file";

my $raw = <$df>;

my $arrayref=unserialize($raw);


my %hash = %{$arrayref};
foreach my $field (keys %hash)
{
	prField($field, $hash{$field});
}
#load pdf form
prDoc($srcfile);

close $df;

prEnd();
